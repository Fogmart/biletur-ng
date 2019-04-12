<?php

namespace common\components;

use Yii;

/**
 * @author Исаков Владислав <visakov@biletur.ru>
 */
class IpGeoBase extends \himiklab\ipgeobase\IpGeoBase {
	const DB_IP_INSERTING_ROWS = 1; // максимальный размер (строки) пакета для INSERT запроса

	/**
	 * Метод производит заполнение таблиц IP-адресов используя
	 * данные из файла self::ARCHIVE_IPS.
	 *
	 * @param $zip \ZipArchive
	 *
	 * @throws \yii\db\Exception
	 */
	protected function generateIpTable($zip) {
		$ipsArray = explode("\n", $zip->getFromName(self::ARCHIVE_IPS_FILE));
		array_pop($ipsArray); // пустая строка

		$i = 0;
		$values = [];
		Yii::$app->{$this->db}->createCommand()->truncateTable(self::DB_IP_TABLE_NAME)->execute();
		foreach ($ipsArray as $ip) {
			$row = explode("\t", $ip);
			$values[++$i] = [$row[0], $row[1], $row[3], ($row[4] !== '-' ? $row[4] : 0)];

			if ($i === self::DB_IP_INSERTING_ROWS) {
				Yii::$app->{$this->db}->createCommand()->batchInsert(
					self::DB_IP_TABLE_NAME,
					['ip_begin', 'ip_end', 'country_code', 'city_id'],
					$values
				)->execute();

				$i = 0;
				$values = [];
				continue;
			}
		}

		// оставшиеся строки не вошедшие в пакеты
		Yii::$app->{$this->db}->createCommand()->batchInsert(
			self::DB_IP_TABLE_NAME,
			['ip_begin', 'ip_end', 'country_code', 'city_id'],
			$values
		)->execute();
	}
}