<?php

namespace common\components\migrate;

use common\base\helpers\StringHelper;
use Yii;
use yii\base\Component;
use yii\db\Connection;
use yii\di\Instance;
use yii\helpers\Console;

/**
 * Компонент для работы с миграциями.
 *
 * Прежде всего, компонент позволяет искать и применять миграции из папок модулей.
 * В каждом модуле миграции должны находиться в директории migrations, например:
 * /common/modules/user/migrations/m150101_000000_init.php
 *
 * Также этот компонент отвязывает работу с миграциями от контроллера.
 * В реализации Yii нельзя получить информацию о новых/применённых миграциях другими компонентами.
 * Также нельзя применить миграции - всё это делается только командой из консоли.
 * А такое поведение не годится, в частности, для поднятия дампа тестовой базы данных.
 *
 *
 */
class MigrateProvider extends Component {
	/**
	 * Параметры подключения к базе данных для выполнения миграций.
	 * Этот параметр необходим, в частности, чтобы выполнять миграции от пользователя с определёнными правами.
	 *
	 * @var Connection|array|string
	 */
	public $db = 'db';
	const ATTR_DB = 'db';

	/** @var MigrateProviderTable Компонент для работы с таблицами. */
	protected $_table;

	/** @var MigrateProviderSchema Компонент для работы со схемой (функциями и вьюшками). */
	protected $_schema;

	/**
	 * @inheritdoc
	 *
	 * @author Залатов Александр <zalatov.ao@dns-shop.ru>
	 */
	public function init() {
		$this->db = Instance::ensure($this->db, Connection::class);

		parent::init();
	}

	/**
	 * Получение компонента для работы с таблицами.
	 *
	 * @return MigrateProviderTable
	 *
	 * @author Залатов Александр <zalatov.ao@dns-shop.ru>
	 */
	protected function getTable() {
		if (null === $this->_table) {
			$this->_table = new MigrateProviderTable($this);
		}

		return $this->_table;
	}

	/**
	 * Получение компонента для работы со схемой (функциями и вьюшками).
	 *
	 * @return MigrateProviderSchema
	 *
	 * @author Залатов Александр <zalatov.ao@dns-shop.ru>
	 */
	protected function getSchema() {
		if (null === $this->_schema) {
			$this->_schema = new MigrateProviderSchema($this);
		}

		return $this->_schema;
	}

	/**
	 * Накат миграций.
	 *
	 * @param mixed $filter Параметры фильтрации миграций
	 *
	 * @return bool
	 *
	 *
	 */
	public function migrateUp($filter = null) {
		$time = microtime(true);

		// -- Получаем список изменений
		$tableUpdates = $this->getTable()->getUpgradesList($filter);
		$schemaUpdates = $this->getSchema()->getUpgradesList();

		if (0 === count($tableUpdates) + count($schemaUpdates)) {
			return true;
		}
		// -- -- -- --

		// -- Выводим список изменений в таблицах
		if (0 !== count($tableUpdates)) {
			$this->stdout(implode(' ', [
				'Доступно',
				count($tableUpdates),
				StringHelper::countPostfix(count($tableUpdates), ['изменение', 'изменения', 'изменений'], null, false),
				'в таблицах:',
				PHP_EOL
			]), Console::FG_YELLOW);
			foreach ($tableUpdates as $info) {
				$this->stdout("\t" . $info . PHP_EOL);
			}
		}
		// -- -- -- --

		// -- Выводим список изменений в схеме
		if (0 !== count($schemaUpdates)) {
			$this->stdout(implode(' ', [
				'Доступно',
				count($schemaUpdates),
				StringHelper::countPostfix(count($schemaUpdates), ['изменение', 'изменения', 'изменений'], null, false),
				'в схеме:',
				PHP_EOL
			]), Console::FG_YELLOW);
			foreach ($schemaUpdates as $info) {
				$this->stdout("\t" . $info . PHP_EOL);
			}
		}
		// -- -- -- --

		// -- Запрашиваем подтверждение пользователя
		$this->stdout(PHP_EOL);

		if (true !== $this->confirm('Применить изменения?')) {
			return true;
		}
		// -- -- -- --

		$error = null;

		// -- Применяем изменения в таблицах
		if (null === $error) {
			if (0 !== count($tableUpdates)) {
				$error = $this->getTable()->upgrade($filter);
			}
		}
		// -- -- -- --

		// -- Применяем изменения в схеме
		if (null === $error) {
			if (0 !== count($schemaUpdates)) {
				$error = $this->getSchema()->upgrade();
			}
		}
		// -- -- -- --

		$time = microtime(true) - $time;
		$time = sprintf('%.3f', $time);

		// -- Обрабатываем успешный результат
		if (null === $error) {
			$this->stdout(PHP_EOL . 'Изменения применены успешно (время: ' . $time . ' сек)' . PHP_EOL, Console::FG_GREEN);

			return true;
		}
		// -- -- -- --

		// -- Выводим ошибку
		$this->stdout('Exception: ' . $error->getMessage() . ' (' . $error->getFile() . ':' . $error->getLine() . ')' . PHP_EOL);
		$this->stdout($error->getTraceAsString() . PHP_EOL);
		$this->stdout(PHP_EOL . 'Возникла ошибка, изменения применены не полностью  (время: ' . $time . ' сек)' . PHP_EOL, Console::FG_RED);

		// -- -- -- --

		return false;
	}

	/**
	 * Откат миграций.
	 *
	 * @param mixed $filter Параметры фильтрации миграций
	 *
	 * @return bool
	 *
	 *
	 */
	public function migrateDown($filter = 1) {
		// -- Получаем список изменений
		$downgrades = $this->getTable()->getDowngradesList($filter);

		if (0 === count($downgrades)) {
			return true;
		}
		// -- -- -- --

		// -- Выводим список изменений
		$this->stdout(implode(' ', [
			'Доступно',
			count($downgrades),
			StringHelper::countPostfix(count($downgrades), ['изменение', 'изменения', 'изменений'], null, false),
			'в таблицах:',
			PHP_EOL
		]), Console::FG_YELLOW);
		foreach ($downgrades as $info) {
			$this->stdout("\t" . $info . PHP_EOL);
		}
		// -- -- -- --

		// -- Запрашиваем подтверждение пользователя
		$this->stdout(PHP_EOL);

		if (true !== $this->confirm('Применить изменения?')) {
			return true;
		}
		// -- -- -- --

		$error = $this->getTable()->downgrade($filter);

		// -- Обрабатываем успешный результат
		if (null === $error) {
			$this->stdout(PHP_EOL . 'Изменения применены успешно.' . PHP_EOL, Console::FG_GREEN);

			return true;
		}
		// -- -- -- --

		// -- Выводим возникшую ошибку
		$this->stdout('Exception: ' . $error->getMessage() . ' (' . $error->getFile() . ':' . $error->getLine() . ')' . PHP_EOL);
		$this->stdout($error->getTraceAsString() . PHP_EOL);
		$this->stdout(PHP_EOL . 'Возникла ошибка, изменения применены не полностью.' . PHP_EOL, Console::FG_RED);

		// -- -- -- --

		return false;
	}

	/**
	 * Вывод отладочного сообщения.
	 *
	 * @param string $message Сообщение для вывода
	 * @param string $color   Дополнительное форматирование стиля
	 *
	 *
	 */
	public function stdout($message, $color = null) {
		// -- Выводим только тогда, когда запущено из консоли
		if (Yii::$app instanceof \yii\console\Application) {
			Yii::$app->controller->stdout($message, $color);
		}
		// -- -- -- --
	}

	/**
	 * Вывод подтверждения о дальнейшем выполнении.
	 *
	 * @param string $question Вопрос о подтверждении
	 *
	 * @return bool
	 *
	 *
	 */
	public function confirm($question) {
		// -- Спрашиваем подтверждения только тогда, когда запущено из консоли
		if (Yii::$app instanceof \yii\console\Application) {
			return Yii::$app->controller->confirm($question);
		}

		// -- -- -- --

		return true;
	}
}