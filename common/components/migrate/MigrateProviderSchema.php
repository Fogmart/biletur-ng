<?php

namespace common\components\migrate;

use common\base\postgres\PgFunction;
use common\base\postgres\PgMatView;
use common\base\postgres\PgView;
use common\modules\core\models\RefMigrationSchema;
use common\yii\base\Object;
use common\yii\helpers\ArrayHelper;
use GlobIterator;
use Throwable;
use Yii;
use yii\base\BaseObject;
use yii\db\Query;
use yii\helpers\Console;

/**
 * Дополнительный провайдер для применения миграций к схеме (функциям и вьюшкам).
 *
 *
 */
class MigrateProviderSchema extends BaseObject {
	/**
	 * Родительский класс, в которому относится этот компонент.
	 * Провайдер включает себя общие методы для работы с базой данных, логирование событий и отловом/выводом ошибок.
	 *
	 * @var MigrateProvider
	 */
	protected $provider;

	/**
	 * Runtime-кэш для списка изменений.
	 * Используется, чтобы каждый раз при вызове метода:
	 * - Не проходиться по файловой системе и не искать функции и вьюшки;
	 * - Не делать лишний запрос в базу.
	 *
	 * @var string[] Ключом является имя класса (с namespace'ом) вьюшки или функции, значением - текстовое представление для отображения
	 */
	protected $_upgradesList;

	/** Текущая версия схемы (менять тогда, когда есть существенные изменения, не совместимые с предыдущими версиями). */
	const SCHEMA_VERSION = 'v_170203_161207';

	/** Название таблицы, в которой будет храниться информация о прошедших изменениях. */
	const HISTORY_TABLE = 'ref_migration_schema';

	/**
	 * @param MigrateProvider $provider
	 *
	 * @author Залатов Александр <zalatov.ao@dns-shop.ru>
	 */
	public function __construct($provider) {
		$this->provider = $provider;

		parent::__construct();
	}

	/**
	 * Получение списка изменений.
	 * Используется в том числе для вывода этой информации пользователю.
	 *
	 * @return string[] Ключом является имя класса (с namespace'ом) вьюшки или функции, значением - текстовое представление для отображения
	 *
	 *
	 */
	public function getUpgradesList() {
		if (null === $this->_upgradesList) {
			$this->createHistoryTable();

			// -- Получаем хэши функций и вьюшек, которые в данный момент в базе
			$hashes = (new Query)
				->select([
					RefMigrationSchema::ATTR_OBJECT,
					RefMigrationSchema::ATTR_DEFINITION_HASH,
				])
				->from(static::HISTORY_TABLE)
				->where([RefMigrationSchema::ATTR_SCHEMA => static::SCHEMA_VERSION])
				->createCommand($this->provider->db)
				->queryAll();

			$hashes = ArrayHelper::map($hashes, RefMigrationSchema::ATTR_OBJECT, RefMigrationSchema::ATTR_DEFINITION_HASH);
			/** @var string[] $hashes Ключом является название объекта, значением - хэш версии в базе. */
			// -- -- -- --

			// -- Проверяем функции
			$functions = [];

			foreach ($this->getFunctions() as $className) {
				$instance = new $className;
				/** @var PgFunction $instance */

				// -- Проверяем, именилось ли определение объекта
				if (array_key_exists($instance::functionName(), $hashes)) {
					if ($instance->getDefinitionHash() === $hashes[$instance::functionName()]) {
						continue;
					}
				}
				// -- -- -- --

				$functions[$className] = $instance::functionName();
			}

			asort($functions);
			// -- -- -- --

			// -- Проверяем вьюшки
			$views = [];

			foreach ($this->getViews() as $className) {
				$instance = new $className;
				/** @var PgView|PgMatView $instance */

				// -- Проверяем, именилось ли определение объекта
				if (array_key_exists($instance::tableName(), $hashes)) {
					if ($instance->getDefinitionHash() === $hashes[$instance::tableName()]) {
						continue;
					}
				}
				// -- -- -- --

				$views[$className] = $instance::tableName();
			}

			asort($views);
			// -- -- -- --

			$this->_upgradesList = array_merge($functions, $views);
		}

		return $this->_upgradesList;
	}

	/**
	 * Накат миграций.
	 *
	 * @return Throwable|null Возвращает возникшую ошибку или NULL, если ошибок нет
	 *
	 *
	 */
	public function upgrade() {
		// -- Создаём схему и указываем, где теперь искать вьюшки и функции
		$this->provider->db->createCommand('CREATE SCHEMA IF NOT EXISTS ' . static::SCHEMA_VERSION)->execute();
		$this->provider->db->createCommand('SET SESSION search_path TO ' . static::SCHEMA_VERSION . ',public')->execute();
		// -- -- -- --

		$result = $this->upgradeFunctions();
		if (null !== $result) {
			return $result;
		}

		$result = $this->upgdateViews();
		if (null !== $result) {
			return $result;
		}

		return null;
	}

	/**
	 * Обновление функций.
	 *
	 * @return Throwable|null Возвращает возникшую ошибку или NULL, если ошибок нет
	 *
	 * @author Залатов Александр <zalatov.ao@dns-shop.ru>
	 */
	protected function upgradeFunctions() {
		$updates = $this->getUpgradesList();

		foreach ($this->getFunctions() as $className) {
			// -- Проверяем, именилось ли определение объекта
			if (false === array_key_exists($className, $updates)) {
				continue;
			}
			// -- -- -- --

			$this->provider->stdout('*** Создание функции ' . $className . PHP_EOL, Console::FG_YELLOW);

			// -- Создаём функцию
			$time = microtime(true);
			$error = $this->createFunction(new $className);
			$time = microtime(true) - $time;
			$time = sprintf('%.3f', $time);
			// -- -- -- --

			// -- Проверяем результат
			if (null === $error) {
				$this->provider->stdout('*** Создана функция ' . $className . ' (время: ' . $time . ' сек)' . PHP_EOL . PHP_EOL, Console::FG_GREEN);
			}
			else {
				$this->provider->stdout('*** Ошибка создания функции ' . $className . ' (время: ' . $time . ' сек)' . PHP_EOL . PHP_EOL, Console::FG_RED);

				return $error;
			}
			// -- -- -- --
		}

		return null;
	}

	/**
	 * Обновление вьюшек.
	 *
	 * @return Throwable|null Возвращает возникшую ошибку или NULL, если ошибок нет
	 *
	 *
	 */
	protected function upgdateViews() {
		$updates = $this->getUpgradesList();

		// -- Получаем список вьюшек и экземпляры соответствующих объектов
		$views = [];
		/** @var PgView[]|PgMatView[] $views */

		foreach ($this->getViews() as $className) {
			// -- Проверяем, именилось ли определение объекта
			if (false === array_key_exists($className, $updates)) {
				continue;
			}
			// -- -- -- --

			$views[$className] = new $className;
		}
		// -- -- -- --

		// -- Добавляем в список те вьюшки, которые зависят от изменённых
		foreach ($views as $className => $instance) {
			foreach ($instance->getDependencies() as $dependency) {
				if (false === array_key_exists($dependency, $views)) {
					$views[$className] = new $className;
				}
			}
		}
		// -- -- -- --

		// -- Выполняем в цикле, пока есть вьюшки - таким образом будет соблюдена зависимость
		while (0 !== count($views)) {
			foreach ($views as $className => $instance) {
				// -- Если вьюшки, от которой зависит эта вьюшка, ещё не созданы, то пропускаем эту вьюшку
				if (0 !== count(array_intersect($instance->getDependencies(), array_keys($views)))) {
					continue;
				}
				// -- -- -- ---

				// -- Создаём вьюшку
				$this->provider->stdout('*** Создание вьюшки ' . $className . PHP_EOL, Console::FG_YELLOW);
				$time = microtime(true);
				$error = $this->createView($instance);
				$time = microtime(true) - $time;
				$time = sprintf('%.3f', $time);
				// -- -- -- --

				// -- Проверяем результат
				if (null === $error) {
					$this->provider->stdout('*** Создана вьюшка ' . $className . ' (время: ' . $time . ' сек)' . PHP_EOL . PHP_EOL, Console::FG_GREEN);
				}
				else {
					$this->provider->stdout('*** Ошибка создания вьюшки ' . $className . ' (время: ' . $time . ' сек)' . PHP_EOL . PHP_EOL, Console::FG_RED);

					return $error;
				}
				// -- -- -- --

				unset($views[$className]);// Удаляем из буфера оставшихся вьюшек
			}
		}

		// -- -- -- --

		return null;
	}

	/**
	 * Создание указанной функции.
	 *
	 * @param PgFunction $function Объект функции
	 *
	 * @return Throwable|null Возвращает возникшую ошибку или NULL, если ошибок нет
	 *
	 *
	 */
	protected function createFunction($function) {
		$transaction = $this->provider->db->beginTransaction();

		try {
			$sql = $function->getDefinition()->getSql();
			$this->provider->stdout($sql . PHP_EOL, Console::FG_GREY);
			$this->provider->db->pdo->exec($sql);
			$this->deleteHistory($function::functionName());
			$this->createHistory($function::functionName(), $function->getDefinitionHash());
			$transaction->commit();
		}
		catch (Throwable $e) {
			$transaction->rollBack();

			return $e;
		}

		return null;
	}

	/**
	 * Создание указанной вьюшки.
	 *
	 * @param PgView|PgMatView $view Объект вьющки
	 *
	 * @return Throwable|null Возвращает возникшую ошибку или NULL, если ошибок нет
	 *
	 *
	 */
	protected function createView($view) {
		$transaction = $this->provider->db->beginTransaction();

		try {
			$sql = $view->getDefinition()->getSql();
			$this->provider->stdout($sql . PHP_EOL, Console::FG_GREY);
			$this->provider->db->pdo->exec($sql);
			$this->deleteHistory($view::tableName());
			$this->createHistory($view::tableName(), $view->getDefinitionHash());
			$transaction->commit();
		}
		catch (Throwable $e) {
			$transaction->rollBack();

			return $e;
		}

		return null;
	}

	/**
	 * Получение списка функций.
	 *
	 * @return string[]
	 *
	 *
	 */
	protected function getFunctions() {
		$result = [];

		// -- Сначала ищем основные функции (безмодульные)
		foreach (new GlobIterator(Yii::getAlias('@common/models/functions/*.php')) as $item) {
			/** @var GlobIterator $item */
			$result[] = $item->getPathname();
		}
		// -- -- -- --

		// -- Затем проходимся по каждому модулю и ищем для него
		foreach (new GlobIterator(Yii::getAlias('@common/modules/*')) as $moduleItem) {
			/** @var GlobIterator $moduleItem */
			// -- Пропускаем всё, что не является папками модулей
			if (false === is_dir($moduleItem->getPathname())) {
				continue;
			}
			if ('.' === $moduleItem->getFilename() || '..' === $moduleItem->getFilename()) {
				continue;
			}
			if (false === file_exists($moduleItem->getPathname() . '/models/functions')) {
				continue;
			}
			// -- -- -- --

			// -- Проходимся по каждой функции
			foreach (new GlobIterator($moduleItem->getPathname() . '/models/functions/*.php') as $migrationItem) {
				/** @var GlobIterator $migrationItem */
				$result[] = $migrationItem->getPathname();
			}
			// -- -- -- --
		}
		// -- -- -- --

		// -- Переводим имена файлов в названия классов
		foreach ($result as $i => $fileName) {
			$className = $fileName;

			$className = str_replace(dirname(Yii::getAlias('@common')), '', $className);
			$className = str_replace('/', '\\', $className);
			$className = str_replace('.php', '', $className);
			$className = ltrim($className, '\\');

			$result[$i] = $className;
		}

		// -- -- -- --

		return $result;
	}

	/**
	 * Получение списка вьюшек.
	 *
	 * @return string[]
	 *
	 *
	 */
	protected function getViews() {
		$result = [];

		// -- Сначала ищем основные вьюшки (безмодульные)
		foreach (new GlobIterator(Yii::getAlias('@common/models/views/*.php')) as $item) {
			/** @var GlobIterator $item */
			$result[] = $item->getPathname();
		}
		// -- -- -- --

		// -- Затем проходимся по каждому модулю и ищем для него
		foreach (new GlobIterator(Yii::getAlias('@common/modules/*')) as $moduleItem) {
			/** @var GlobIterator $moduleItem */
			// -- Пропускаем всё, что не является папками модулей
			if (false === is_dir($moduleItem->getPathname())) {
				continue;
			}
			if ('.' === $moduleItem->getFilename() || '..' === $moduleItem->getFilename()) {
				continue;
			}
			if (false === file_exists($moduleItem->getPathname() . '/models/views')) {
				continue;
			}
			// -- -- -- --

			// -- Проходимся по каждой функции
			foreach (new GlobIterator($moduleItem->getPathname() . '/models/views/*.php') as $migrationItem) {
				/** @var GlobIterator $migrationItem */
				$result[] = $migrationItem->getPathname();
			}
			// -- -- -- --
		}
		// -- -- -- --

		// -- Переводим имена файлов в названия классов
		foreach ($result as $i => $fileName) {
			$className = $fileName;

			$className = str_replace(dirname(Yii::getAlias('@common')), '', $className);
			$className = str_replace('/', '\\', $className);
			$className = str_replace('.php', '', $className);
			$className = ltrim($className, '\\');

			$result[$i] = $className;
		}

		// -- -- -- --

		return $result;
	}

	/**
	 * Удаление записи из истории.
	 *
	 * @param string $objectName Название объекта
	 *
	 *
	 */
	protected function deleteHistory($objectName) {
		$this->provider->db->createCommand()->delete(static::HISTORY_TABLE, [
			RefMigrationSchema::ATTR_SCHEMA => static::SCHEMA_VERSION,
			RefMigrationSchema::ATTR_OBJECT => $objectName,
		])->execute();
	}

	/**
	 * Добавление запись в историю.
	 *
	 * @param string $objectName     Название объекта
	 * @param string $definitionHash Хэш от SQL кода, создающего объект
	 *
	 *
	 */
	protected function createHistory($objectName, $definitionHash) {
		$this->provider->db->createCommand()->insert(static::HISTORY_TABLE, [
			RefMigrationSchema::ATTR_SCHEMA          => static::SCHEMA_VERSION,
			RefMigrationSchema::ATTR_OBJECT          => $objectName,
			RefMigrationSchema::ATTR_DEFINITION_HASH => $definitionHash,
		])->execute();
	}

	/**
	 * Проверка и создание таблицы, в которой будет храниться информация об изменениях в схеме.
	 *
	 *
	 */
	protected function createHistoryTable() {
		// -- Если таблица уже есть, то сразу выходим
		if (null !== $this->provider->db->schema->getTableSchema(static::HISTORY_TABLE, true)) {
			return;
		}
		// -- -- -- --

		$this->provider->stdout('Создание таблицы ' . $this->provider->db->schema->getRawTableName(static::HISTORY_TABLE) . '...', Console::FG_YELLOW);

		// -- Создаём таблицу
		$this->provider->db->createCommand()->createTable(static::HISTORY_TABLE, [
			RefMigrationSchema::ATTR_SCHEMA          => 'VARCHAR(64)     NOT NULL',// Название схемы
			RefMigrationSchema::ATTR_OBJECT          => 'VARCHAR(255)    NOT NULL',// Название объекта (функции или вьюшки)
			RefMigrationSchema::ATTR_DEFINITION_HASH => 'CHAR(32)        NOT NULL',// Хэш содеримого SQL, который создаёт объект (чтобы проверять изменения)
			RefMigrationSchema::ATTR_APPLY_STAMP     => 'TIMESTAMP       NOT NULL DEFAULT TIMEZONE(\'UTC\', now())',
		])->execute();
		// -- -- -- --

		// -- Добавляем индекс
		$this->provider->db->createCommand()->createIndex(
			'ux-' . static::HISTORY_TABLE . '[' . RefMigrationSchema::ATTR_SCHEMA . ',' . RefMigrationSchema::ATTR_OBJECT . ']',
			static::HISTORY_TABLE,
			[RefMigrationSchema::ATTR_SCHEMA, RefMigrationSchema::ATTR_OBJECT],
			true
		)->execute();
		// -- -- -- --

		$this->provider->stdout('Готово!' . PHP_EOL, Console::FG_GREEN);
	}
}