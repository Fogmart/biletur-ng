<?php

namespace common\components\migrate;

use common\modules\core\models\RefMigration;
use common\yii\base\Object;
use console\base\Migration;
use GlobIterator;
use InvalidArgumentException;
use Throwable;
use Yii;
use yii\base\BaseObject;
use yii\db\Query;
use yii\helpers\Console;

/**
 * Дополнительный провайдер для применения миграций к таблицам.
 *
 *
 */
class MigrateProviderTable extends BaseObject {
	/** @var MigrateProvider Родительский класс, в которому относится этот компонент. */
	protected $provider;

	/**
	 * Список всех миграций, которые есть в проекте.
	 * Фактически, это runtime-кэш, чтобы каждый раз не проходиться по файловой системе.
	 * Ключом массива является имя файла, значением - версия миграции.
	 *
	 * @var string[]
	 */
	protected $allMigrations;

	/** Название таблицы, в которой будет храниться информация о прошедших изменениях. */
	const HISTORY_TABLE = 'ref_migration';

	/**
	 * @param MigrateProvider $provider
	 *
	 *
	 */
	public function __construct($provider) {
		$this->provider = $provider;

		parent::__construct();
	}

	/**
	 * Получение списка миграций, которые будут применены.
	 * Используется в том числе для вывода этой информации пользователю.
	 *
	 * @param mixed $filter Параметры отсева миграций
	 *
	 * @return string[]
	 *
	 * @uses filterMigrations() Параметры отсева миграций используются этим методом
	 *
	 *
	 */
	public function getUpgradesList($filter) {
		return $this->filterMigrations($this->getNewMigrations(), $filter);
	}

	/**
	 * Получение списка миграций, которые будут отменены.
	 * Используется в том числе для вывода этой информации пользователю.
	 *
	 * @param mixed $filter Параметры отсева миграций
	 *
	 * @return string[]
	 *
	 * @uses filterMigrations() Параметры отсева миграций используются этим методом
	 *
	 *
	 */
	public function getDowngradesList($filter = 1) {
		return $this->filterMigrations($this->getAppliedMigration(), $filter);
	}

	/**
	 * Поиск указанной миграции и создание объекта, реализующего указанную миграцию.
	 *
	 * @param string $version Версия миграции
	 *
	 * @return Migration|null Возвращает объект миграции или NULL, если указанной миграции не найдено
	 *
	 *
	 */
	protected function getMigrationInstance($version) {
		// -- Поиск указанной миграции среди всех миграций проекта
		$fileName = array_search($version, $this->getAllMigrations());
		if (false === $fileName) {
			return null;
		}
		// -- -- -- --

		// -- Проверяем существование класса
		require_once($fileName);
		if (false === class_exists($version)) {
			return null;
		}

		// -- -- -- --

		return new $version([Migration::ATTR_DB => $this->provider->db]);
	}

	/**
	 * Получение версии миграции исходя из названия класса миграции.
	 *
	 * @param string $name Название, из которого получить версию
	 *
	 * @return string|bool Возвращает версию миграции или FALSE, если указанный параметр не является классом миграции
	 *
	 *
	 */
	protected function getMigrationVersion($name) {
		$name = basename($name, '.php');// Миграции указываются в PHP файлах, поэтому отрезаем всё лишнее

		if (1 === preg_match('/^m\d{6}_\d{6}_.+?$/', $name, $matches)) {
			return $matches[0];
		}

		return false;
	}

	/**
	 * Получение списка применённых миграций.
	 *
	 * @return string[]
	 *
	 *
	 */
	protected function getAppliedMigration() {
		$this->createHistoryTable();

		// -- Получаем список применённых миграций
		$migrations = (new Query)
			->select(RefMigration::ATTR_VERSION)
			->from(static::HISTORY_TABLE)
			->orderBy([RefMigration::ATTR_VERSION => SORT_DESC])
			->createCommand($this->provider->db)
			->queryColumn();

		// -- -- -- --

		return $migrations;
	}

	/**
	 * Фильтрация миграций согласно параметрам, которые указал пользователь.
	 *
	 * @param string[]        $migrations       Список миграций, которые отфильтровать
	 * @param string|int|null $filter           Указанный пользователем фильтр. Доступны несколько вариантов:
	 *                                          - Если указана строка, то будет применена указанная миграция
	 *                                          - Если указано число, то будут применены первые N миграций
	 *                                          - Если ничего не указано, будут применены все миграции
	 *
	 * @return string[]
	 *
	 *
	 */
	protected function filterMigrations($migrations, $filter) {
		// -- Если фильтр не указан, то ничего и не фильтруем
		if (null === $filter) {
			return $migrations;
		}
		// -- -- -- --

		// -- Если указано число, то значит надо применить указанные N миграций
		if (is_numeric($filter)) {
			$limit = (int)$filter;

			return array_slice($migrations, 0, $limit);
		}
		// -- -- -- --

		// -- Во всех других случаях считаем, что указана конкретная миграция
		$version = $this->getMigrationVersion($filter);
		if (false !== $version) {
			return [$version];
		}

		// -- -- -- --

		return [];
	}

	/**
	 * Получение списка новых миграций.
	 *
	 * @return string[]
	 *
	 *
	 */
	public function getNewMigrations() {
		return array_diff($this->getAllMigrations(), $this->getAppliedMigration());
	}

	/**
	 * Получение списка всех миграций в проекте.
	 *
	 * @return string[] Ключом является полный путь до файла миграции, значением - версия
	 *
	 *
	 */
	protected function getAllMigrations() {
		// -- Если миграции ещё не были получены, получаем их
		if (null === $this->allMigrations) {
			$migrations = [];

			// -- Сначала ищем основные миграции (безмодульные)
			foreach (new GlobIterator(Yii::getAlias('@console/migrations/*.php')) as $item) {
				/** @var GlobIterator $item */
				$version = $this->getMigrationVersion($item->getFilename());
				if (false !== $version) {
					$migrations[$item->getPathname()] = $version;
				}
			}
			// -- -- -- --

			// -- Затем проходимся по каждому модулю и ищем для него миграции
			foreach (new GlobIterator(Yii::getAlias('@common/modules/*')) as $moduleItem) {
				/** @var GlobIterator $moduleItem */
				// -- Пропускаем всё, что не является папками модулей
				if (false === is_dir($moduleItem->getPathname())) {
					continue;
				}
				if ('.' === $moduleItem->getFilename() || '..' === $moduleItem->getFilename()) {
					continue;
				}
				if (false === file_exists($moduleItem->getPathname() . '/migrations')) {
					continue;
				}
				// -- -- -- --

				// -- Проходимся по каждой миграции
				foreach (new GlobIterator($moduleItem->getPathname() . '/migrations/*.php') as $migrationItem) {
					/** @var GlobIterator $migrationItem */
					$version = $this->getMigrationVersion($migrationItem->getFilename());
					if (false !== $version) {
						$migrations[$migrationItem->getPathname()] = $version;
					}
				}
				// -- -- -- --
			}
			// -- проходим по подмодулям АПИ
			foreach (new GlobIterator(Yii::getAlias('@common/modules/api/*')) as $moduleItem) {
				/** @var GlobIterator $moduleItem */
				// -- Пропускаем всё, что не является папками модулей
				if (false === is_dir($moduleItem->getPathname())) {
					continue;
				}
				if ('.' === $moduleItem->getFilename() || '..' === $moduleItem->getFilename()) {
					continue;
				}
				if (false === file_exists($moduleItem->getPathname() . '/migrations')) {
					continue;
				}
				// -- -- -- --

				// -- Проходимся по каждой миграции
				foreach (new GlobIterator($moduleItem->getPathname() . '/migrations/*.php') as $migrationItem) {
					/** @var GlobIterator $migrationItem */
					$version = $this->getMigrationVersion($migrationItem->getFilename());
					if (false !== $version) {
						$migrations[$migrationItem->getPathname()] = $version;
					}
				}
				// -- -- -- --
			}
			// -- проходим по подмодулям Сайта
			foreach (new GlobIterator(Yii::getAlias('@common/modules/site/*')) as $moduleItem) {
				/** @var GlobIterator $moduleItem */
				// -- Пропускаем всё, что не является папками модулей
				if (false === is_dir($moduleItem->getPathname())) {
					continue;
				}
				if ('.' === $moduleItem->getFilename() || '..' === $moduleItem->getFilename()) {
					continue;
				}
				if (false === file_exists($moduleItem->getPathname() . '/migrations')) {
					continue;
				}
				// -- -- -- --

				// -- Проходимся по каждой миграции
				foreach (new GlobIterator($moduleItem->getPathname() . '/migrations/*.php') as $migrationItem) {
					/** @var GlobIterator $migrationItem */
					$version = $this->getMigrationVersion($migrationItem->getFilename());
					if (false !== $version) {
						$migrations[$migrationItem->getPathname()] = $version;
					}
				}
				// -- -- -- --
			}
			// -- -- -- --

			asort($migrations);

			$this->allMigrations = $migrations;
		}

		// -- -- -- --

		return $this->allMigrations;
	}

	/**
	 * Накат миграций.
	 *
	 * @param mixed $filter Параметры отсева миграций
	 *
	 * @return Throwable|null Возвращает возникшую ошибку или NULL, если ошибок нет
	 *
	 * @uses filterMigrations() Параметры отсева миграций используются этим методом
	 *
	 *
	 */
	public function upgrade($filter = null) {
		$migrations = $this->getUpgradesList($filter);

		// -- Применяем миграции
		foreach ($migrations as $migration) {
			$this->provider->stdout('*** Применение миграции ' . $migration . PHP_EOL, Console::FG_YELLOW);

			$time = microtime(true);
			$error = $this->migrationUp($migration);
			$time = microtime(true) - $time;
			$time = sprintf('%.3f', $time);

			if (null === $error) {
				$this->provider->stdout('*** Успешное применение миграции ' . $migration . ' (время: ' . $time . ' сек)' . PHP_EOL . PHP_EOL, Console::FG_GREEN);
			}
			else {
				$this->provider->stdout('*** Ошибка применения миграции ' . $migration . ' (время: ' . $time . ' сек)' . PHP_EOL . PHP_EOL, Console::FG_RED);

				return $error;
			}
		}

		// -- -- -- --

		return null;
	}

	/**
	 * Откат миграций.
	 *
	 * @param mixed $filter Параметры отсева миграций
	 *
	 * @return Throwable|null Возвращает возникшую ошибку или NULL, если ошибок нет
	 *
	 * @uses filterMigrations() Параметры отсева миграций используются этим методом
	 *
	 *
	 */
	public function downgrade($filter = 1) {
		$migrations = $this->getDowngradesList($filter);

		// -- Отменяем миграции
		foreach ($migrations as $migration) {
			$this->provider->stdout('*** Откат миграции ' . $migration . PHP_EOL, Console::FG_YELLOW);

			$time = microtime(true);
			$error = $this->migrationDown($migration);
			$time = microtime(true) - $time;
			$time = sprintf('%.3f', $time);

			if (null === $error) {
				$this->provider->stdout('*** Успешный откат миграции ' . $migration . ' (время: ' . $time . ' сек)' . PHP_EOL . PHP_EOL, Console::FG_GREEN);
			}
			else {
				$this->provider->stdout('*** Ошибка отката миграции ' . $migration . ' (время: ' . $time . ' сек)' . PHP_EOL . PHP_EOL, Console::FG_RED);

				return $error;
			}
		}

		// -- -- -- --

		return null;
	}

	/**
	 * Применение указанной миграции.
	 *
	 * @param string $version Версия миграции
	 *
	 * @return Throwable|null Возвращает возникшую ошибку или NULL, если ошибок нет
	 *
	 *
	 */
	protected function migrationUp($version) {
		// -- Получаем объект миграции
		$migration = $this->getMigrationInstance($version);
		if (null === $migration) {
			return new InvalidArgumentException('Миграция не найдена: ' . $version);
		}
		// -- -- -- --

		// -- Применяем миграцию
		$result = (false !== $migration->up());

		if (true === $result) {
			$this->createHistory($version);

			return null;
		}

		// -- -- -- --

		return new InvalidArgumentException('Возникла ошибка');// Тут мы не можем получить ошибку, потому что в $migration->up() она перехватывается
	}

	/**
	 * Откат указанной миграции.
	 *
	 * @param string $version Версия миграции
	 *
	 * @return Throwable|null Возвращает возникшую ошибку или NULL, если ошибок нет
	 *
	 *
	 */
	protected function migrationDown($version) {
		// -- Получаем объект миграции
		$migration = $this->getMigrationInstance($version);
		if (null === $migration) {
			return new InvalidArgumentException('Миграция не найдена: ' . $version);
		}
		// -- -- -- --

		// -- Откатываем миграцию
		$result = (false !== $migration->down());

		if (true === $result) {
			$this->deleteHistory($version);

			return null;
		}

		// -- -- -- --

		return new InvalidArgumentException('Возникла ошибка');// Тут мы не можем получить ошибку, потому что в $migration->down() она перехватывается
	}

	/**
	 * Удаление записи из истории.
	 *
	 * @param string $version Версия миграции
	 *
	 *
	 */
	protected function deleteHistory($version) {
		$this->provider->db->createCommand()->delete(static::HISTORY_TABLE, [
			RefMigration::ATTR_VERSION => $version,
		])->execute();
	}

	/**
	 * Добавление запись в историю.
	 *
	 * @param string $version Версия миграции
	 *
	 *
	 */
	protected function createHistory($version) {
		$this->provider->db->createCommand()->insert(static::HISTORY_TABLE, [
			RefMigration::ATTR_VERSION    => $version,
			RefMigration::ATTR_APPLY_TIME => time(),
		])->execute();
	}

	/**
	 * Проверка и создание таблицы, в которой будет храниться информация о применённых миграциях.
	 *
	 *
	 */
	protected function createHistoryTable() {
		// -- Если таблица уже есть, то сразу выходим
		if (null !== $this->provider->db->schema->getTableSchema(static::HISTORY_TABLE, true)) {
			return;
		}
		// -- -- -- --

		// -- Создаём таблицу
		$this->provider->stdout('Создание таблицы ' . $this->provider->db->schema->getRawTableName(static::HISTORY_TABLE) . '...', Console::FG_YELLOW);

		$this->provider->db->createCommand()->createTable(static::HISTORY_TABLE, [
			RefMigration::ATTR_VERSION    => 'VARCHAR(180) NOT NULL PRIMARY KEY',
			RefMigration::ATTR_APPLY_TIME => 'INTEGER',
		])->execute();

		$this->provider->stdout('Готово!' . PHP_EOL, Console::FG_GREEN);
		// -- -- -- --
	}
}