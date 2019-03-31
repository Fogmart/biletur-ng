<?php

namespace common\models\oracle\scheme\sns;

use common\interfaces\InvalidateModels;
use yii\db\ActiveRecord;

/**
 *
 * @author isakov.v
 *
 * Модель табло рейсов, реазлизует интерфейс InvalidateModels
 *
 * Поля таблицы:
 * @property string $city
 * @property int    $tablomode
 * @property string $stoptime
 * @property string $starttime
 * @property string $dt
 * @property string $aprname
 * @property string $apename
 * @property string $apmode
 * @property string $apclsrsn
 * @property string $apclsdt
 * @property string $apopndt
 * @property int    $gmtshift
 * @property string $autostart
 * @property string $autostop
 * @property int    $mrgnbefarr
 * @property int    $mrgnaftarr
 * @property int    $mrgnaftdep
 * @property string $whochng
 * @property string $whnchng
 */
class APStatus extends ActiveRecord implements InvalidateModels {

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{SNS.AP_STATUS}}';
	}

	public static function getAirportName($apCode) {
		$airports = self::_airportsName();

		return $airports[strtoupper($apCode)];
	}

	private static function _airportsName() {
		return [
			'VVO' => 'Владивосток',
			'PKC' => 'Петропавловск-Камчатский'
		];
	}

	/**
	 * Периодичность проверки актуальности кеша
	 * @return int
	 */
	public function getInvalidateTime() {
		return 60 * 60 * 3;
	}

	/**
	 * Поле в таблице по которому проверям актуальность
	 * @return string
	 */
	public function getInvalidateField() {
		return 'WHNCHNG';
	}
}