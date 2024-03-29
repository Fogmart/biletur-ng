<?php

namespace common\models;

use common\components\SiteModel;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\log\Logger;

/**
 * Модель лога, записанного в базу.
 *
 * Столбцы в таблице:
 * @property int    $id             Уникальный идентификатор
 * @property int    $level          Уровень (ошибка, предупреждение и т.п.)
 * @property string $category       Категория
 * @property string $log_time       Дата и время лога
 * @property string $prefix         Префикс лога
 * @property string $message        Текст лога
 * @property string $hostname       Имя хоста
 * @property string $site_id        Идентификатор сайта, на котором выполнялся скрипт
 *
 */
class LogYii extends SiteModel {
	const ATTR_ID = 'id';
	const ATTR_LEVEL = 'level';
	const ATTR_CATEGORY = 'category';
	const ATTR_LOG_TIME = 'log_time';
	const ATTR_PREFIX = 'prefix';
	const ATTR_MESSAGE = 'message';
	const ATTR_HOSTNAME = 'hostname';
	const ATTR_SITE_ID = 'site_id';

	const SITE_BILETUR = 0;
	const SITE_VISA_BILETUR = 1;

	const SITE_NAMES = [
		self::SITE_BILETUR      => 'https://biletur.ru',
		self::SITE_VISA_BILETUR => 'https://visa.biletur.ru',
	];

	/**
	 * @return array
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function behaviors() {
		return [
			[
				'class'              => TimestampBehavior::class,
				'createdAtAttribute' => 'log_time',
				'updatedAtAttribute' => 'log_time',
				'value'              => new Expression('sysdate'),
			],
		];
	}

	/**
	 * @return string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function tableName() {
		return '{{%log_yii}}';
	}

	/**
	 * @inheritdoc
	 *
	 *
	 */
	public function attributeLabels() {
		return [
			static::ATTR_ID       => 'Идентификатор',
			static::ATTR_LEVEL    => 'Уровень',
			static::ATTR_CATEGORY => 'Категория',
			static::ATTR_LOG_TIME => 'Дата и время',
			static::ATTR_PREFIX   => 'Префикс',
			static::ATTR_MESSAGE  => 'Тест лога',
			static::ATTR_HOSTNAME => 'Хост',
			static::ATTR_SITE_ID  => 'Сайт',
		];
	}

	/**
	 * Получение заголовка ошибки из всего сообщения об ошибке.
	 *
	 * @return string
	 *
	 *
	 */
	public function getTitle() {
		$title = explode("\n", $this->message);
		$title = $title[0];

		$title = preg_replace('/(Выполнявшийся SQL-запрос:|The SQL statement executed was:).*$/', '', $title);// Удаляем текст SQL запроса - он не нужен в заголовке
		$title = preg_replace('/in [^f][^\s]+\.php:\d+.*$/', '', $title);// Удаляем путь до файла - он не нужен в заголовке
		$title = trim($title);// Удаляем ненужные пробелы
		$title = preg_replace('/^exception \'[^\']+\' with message \'(.*?)\'$/', '\1', $title);// Удаляем тип исключения, так как он пишется в категорию
		$title = preg_replace('/^.*?SQLSTATE[^:]+: /', '\1', $title);// Удаляем мусор из исключений СУБД
		$title = trim($title);// Повторно удаляем ненужные пробелы

		return $title;
	}

	/**
	 * Получение URL адреса, на котором произошла ошибка.
	 *
	 * @return string|null Возвращает относительный URL адрес или NULL, если адрес извлечь не удалось (например, в консоли)
	 *
	 * @author Залатов Александр <zalatov.ao@dns-shop.ru>
	 */
	public function getUrl() {
		// -- Дописываем страницу, на которой произошла ошибка
		if (1 === preg_match('/REQUEST_URI.*?(\/.*?)\n/', $this->message, $matches)) {
			return $matches[1];
		}

		// -- -- -- --

		return null;
	}

	/**
	 * Получение названия файла и строки, где возникла ошибка.
	 *
	 * @param bool $linkToGitlab Необходимо ли сгенерировать ссылку на просмотр в Гитлабе или же вывести просто текст
	 *
	 * @return string|null Название файла или NULL, если название извлечь не удалось
	 *
	 *
	 */
	public function getFileAndLine() {
		$fileName = null;

		// -- Если удалось извлечь имя файла
		if (1 === preg_match('/ in ([^f][^\s].*?\.php:\d+)[^\d]/', $this->message . '.', $matches)) {
			$fileName = str_replace(dirname(Yii::$app->basePath), '', $matches[1]);
		}
		elseif (1 === preg_match('/#0 ([^f][^\s].*?\.php:\d+)[^\d]/', $this->message . '.', $matches)) {
			$fileName = str_replace(dirname(Yii::$app->basePath), '', $matches[1]);
		}

		return $fileName;
	}

	/**
	 * Получение названия сайта, на котором произошёл лог.
	 *
	 * @param int $id Идентификатор сайта
	 *
	 * @return string|null Название сайта или NULL, если название неизвестно
	 *
	 *
	 */
	public function getSiteName($id) {
		return static::SITE_NAMES[$id];
	}

	/**
	 * Получение названия уровня лога.
	 *
	 * @return string
	 *
	 *
	 */
	public function getLevelName() {
		return Logger::getLevelName($this->level);
	}

	/**
	 * Получение IP адреса пользователя.
	 *
	 * @return string
	 *
	 *
	 */
	public function getUserIp() {
		if (preg_match('/^\[(?P<userIp>.*?)\]\[(?P<userId>.*?)\]\[(?P<session>.*?)\]$/', $this->prefix, $matches)) {
			return $matches['userIp'];
		}

		return null;
	}
}