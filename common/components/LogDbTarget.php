<?php

namespace common\components;


use common\base\helpers\Dump;
use common\models\LogYii;
use Exception;
use yii\helpers\VarDumper;
use yii\log\DbTarget;

/**
 * Компонент для записи логов в базу.
 */
class LogDbTarget extends DbTarget {

	const EXCLUDED_MESSAGE = [];

	/** @inheritdoc */
	public $logVars = [];

	/**
	 * @inheritdoc
	 */
	public function export() {
		$messages = $this->messages;

		// -- Фильтруем сообщения собственным способом
		foreach (static::EXCLUDED_MESSAGE as $exclude) {
			foreach ($messages as $i => $message) {
				if (false !== mb_strpos($message[0], $exclude)) {
					unset($messages[$i]);
				}
			}
		}
		// -- -- -- --

		// -- Если логов нет, то нечего и добавлять в базу
		if (0 === count($messages)) {
			return;
		}
		// -- -- -- --

		// -- Данные, которые одинаковы для всех логов
		$defaultRow = [
			LogYii::ATTR_HOSTNAME => gethostname(),
			LogYii::ATTR_SITE_ID  => 0,
		];
		// -- -- -- --

		// -- Добавляем данные из POST-запроса
		$postInfo = '';

		if (isset($_SERVER['REQUEST_METHOD'])) {
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				$postInfo = Dump::print_r($this->_hidePasswordField($_POST), true);
			}
		}
		// -- -- -- --

		// -- Дополняем сообщение об ошибке дополнительными данными
		$serverInfo = [];

		if (isset($_SERVER['REQUEST_URI'])) {
			$serverInfo[] = 'REQUEST_URI = ' . $_SERVER['REQUEST_URI'];
		}
		if (isset($_SERVER['REQUEST_METHOD'])) {
			$serverInfo[] = 'REQUEST_METHOD = ' . $_SERVER['REQUEST_METHOD'];
		}
		if (isset($_SERVER['REMOTE_ADDR'])) {
			$serverInfo[] = 'REMOTE_ADDR = ' . $_SERVER['REMOTE_ADDR'];
		}
		if (isset($_SERVER['HTTP_USER_AGENT'])) {
			$serverInfo[] = 'HTTP_USER_AGENT = ' . $_SERVER['HTTP_USER_AGENT'];
		}
		if (isset($_SERVER['HTTP_REFERER'])) {
			$serverInfo[] = 'HTTP_REFERER = ' . $_SERVER['HTTP_REFERER'];
		}

		$serverInfo = implode("\n", $serverInfo);
		// -- -- -- --


		// -- Проходимся по каждому сообщению и подготавливаем его для добавления в базу
		$rows = [];

		foreach ($messages as $message) {
			list($text, $level, $category, $timestamp) = $message;

			// -- Определяем текст сообщения
			if (false === is_string($text)) {
				if ($text instanceof Exception) {
					$text = $text->__toString();
				}
				else {
					$text = VarDumper::export($text);
				}
			}
			// -- -- -- --

			// -- Дописываем с текст сообщения дополнительную информацию, если она есть
			if ('' !== $postInfo) {
				$text = $text . "\n\n- - - - - - - - -\n\n" . $postInfo;
			}
			if ('' !== $serverInfo) {
				$text = $text . "\n\n- - - - - - - - -\n\n" . $serverInfo;
			}
			// -- -- -- --


			// -- Привязываем параметры
			$rows[] = array_merge($defaultRow, [
				LogYii::ATTR_LEVEL    => $level,
				LogYii::ATTR_CATEGORY => $category,
				LogYii::ATTR_PREFIX   => $this->getMessagePrefix($message),
				LogYii::ATTR_MESSAGE  => $text,
			]);
			// -- -- -- --
		}
		// -- -- -- --
		foreach ($rows as $row) {
			$logRecord = new LogYii();
			$logRecord->level = $row[LogYii::ATTR_LEVEL];
			$logRecord->hostname = $defaultRow[LogYii::ATTR_HOSTNAME];
			$logRecord->site_id = $defaultRow[LogYii::ATTR_SITE_ID];
			$logRecord->category = $row[LogYii::ATTR_CATEGORY];
			$logRecord->prefix = $row[LogYii::ATTR_PREFIX];
			$logRecord->message = $row[LogYii::ATTR_MESSAGE];

			$logRecord->save();
		}
		// -- -- -- --
	}

	/**
	 * Рекурсивное удаление (скрытие) пароля из данных.
	 *
	 * @param array $data Массив с данными
	 *
	 * @return array
	 *
	 * @author Залатов Александр <zalatov.ao@dns-shop.ru>
	 */
	private function _hidePasswordField($data) {
		foreach ($data as $key => $value) {
			if (is_array($value)) {
				$data[$key] = $this->_hidePasswordField($value);
			}
			else {
				if (false !== stripos($key, 'password')) {
					$data[$key] = '********';
				}
			}
		}

		return $data;
	}
}