<?php

namespace common\components\helpers;

use common\models\scheme\sns\IOrderMessage;
use common\models\scheme\sns\IOrders;
use common\models\scheme\tour\Orders;
use common\models\scheme\tour\OrdPerson;
use PDO;

/**
 * @author isakov.v
 *
 *
 */
class OraHelper {

	public static function convertDate($date) {
		$date = date("d-m-Y", strtotime($date));

		return $date;
	}

	/**
	 * Суперхак для адаптивности картинок из блоб-полей :)))
	 *
	 * @param $blob
	 *
	 * @return mixed
	 */
	public static function addResponsiveToImageFromBlob($blob) {
		$blob = str_replace('<img', '<img class="img-responsive"', $blob);
		$blob = self::modifyLinksToBlobUrl($blob);

		return $blob;
	}

	/**
	 * Еще один хак для модифицирования ссылок в новостях
	 *
	 * @param $blob
	 *
	 * @return mixed
	 */
	public static function modifyLinksToBlobUrl($blob) {
		$blob = str_replace('tour.biletur.ru/Tourism/tour.asp?id=', '212.122.4.24/tours/detail/', $blob);
		$blob = str_replace('tour.biletur.ru/tourism/tour.asp?id=', '212.122.4.24/tours/detail/', $blob);
		$blob = str_replace('tour.airagency.ru/Tourism/tour.asp?id=', '212.122.4.24/tours/detail/', $blob);
		$blob = str_replace('callto', 'tel', $blob);

		return $blob;
	}

	/**
	 * Получение какого-то идентификатора
	 *
	 * @param $name
	 *
	 * @return array|bool
	 */
	public static function getNextSeqVal($name) {
		$sql = 'select ' . $name . '.nextval as ID from dual';
		$connection = \Yii::$app->getDb();
		$row = $connection->createCommand($sql)->queryScalar();

		return $row;
	}

	public static function getOrdNum() {
		$sql = 'select t3.Sq_Ordnum.Nextval as num from dual';
		$connection = \Yii::$app->getDb();
		$row = $connection->createCommand($sql)->queryOne();

		return $row['NUM'];
	}


	/**
	 * Получение 10-ти значного текстового идентификатора для таблицы
	 *
	 * @param string $db
	 *
	 * @return string
	 */
	public static function getAbzId($db = 'TOURS') {
		$id = '';
		$db = self::_getABZSequenceName($db);
		$sql = 'CALL NxtABZID (:db, :id)';
		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->bindParam(":db", $db, PDO::PARAM_STR);
		$command->bindParam(":id", $id, PDO::PARAM_STR, '10');
		$command->execute();

		return $id;
	}

	private static function _getABZSequenceName($modelName) {
		$seq = self::_ABZSequenceNames();
		if (array_key_exists($modelName, $seq)) {
			return $seq[$modelName];
		}

		return 'GLOBAL';
	}

	/**
	 * Соответствие моделей названиям таблиц для генерации ID.
	 * Возможно требует коррекции т.к. я там что-то запутался :(
	 *
	 * @return array
	 */
	private static function _ABZSequenceNames() {
		return [
			IOrders::className()       => 'IORDERS',
			IOrderMessage::className() => 'IORDMSG',
			Orders::className()        => 'TOURS',
			OrdPerson::className()     => 'TOURS'
		];
	}

	/**
	 * Получение следующего номера, например для заказа
	 *
	 * @param string $db
	 *
	 * @return string
	 */
	public static function getNextNum($db = 'GLOBAL') {
		$id = '';
		$sql = 'CALL SNS.NXTNUMID (:db, :id)';
		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->bindParam(":db", $db, PDO::PARAM_STR);
		$command->bindParam(":id", $id, PDO::PARAM_INPUT_OUTPUT);
		$command->execute();

		return $id;
	}

	public static function dateFromDateTime($dateTime) {
		return substr($dateTime, 0, 10);
	}
}