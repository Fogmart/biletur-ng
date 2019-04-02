<?php

namespace common\models\oracle\scheme\sns;

use common\models\oracle\scheme\arr\ARRCity;
use common\models\oracle\scheme\sns\queries\QueryTowns;
use common\models\oracle\scheme\t3\RefItems;
use common\models\oracle\scheme\t3\RIAd;
use Yii;
use yii\caching\TagDependency;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 *
 * @author isakov.v
 *
 * Модель таблицы TOWNS
 *
 * Поля таблицы:
 * @property string                                             $ID
 * @property string                                             $RNAME
 * @property string                                             $ENAME
 * @property string                                             $CODE
 * @property string                                             $STATEID
 * @property string                                             $STATECODE
 * @property string                                             $ADMREGID
 * @property string                                             $ADMCODE
 * @property string                                             $IATACODE
 * @property string                                             $IKAOCODE
 * @property string                                             $PHONECODE
 * @property int                                                $IPCITYID
 * @property int                                                $LATITUDE
 * @property int                                                $LONGITUDE
 * @property string                                             $WHOCRT
 * @property string                                             $WHNCRT
 * @property string                                             $WHOUPD
 * @property string                                             $WHNUPD
 * @property int                                                $AURA_ID
 * @property int                                                $SHWINGUIDE
 * @property string                                             $YNDXWTHRID
 * @property int                                                $GMTSHIFT
 * @property int                                                $SUMMERSHIFT
 * @property int                                                $RANG
 * @property string                                             $NAME
 *
 * @property-read ARRCity                                       $arrCity
 * @property-read \common\models\oracle\scheme\sns\DspPlaces[]  $places
 * @property-read \common\models\oracle\scheme\sns\DspCountries $country
 *
 */
class DspTowns extends ActiveRecord {

	const ATTR_ID = 'ID';
	const ATTR_RNAME = 'RNAME';
	const ATTR_ENAME = 'ENAME';
	const ATTR_CODE = 'CODE';
	const ATTR_STATEID = 'STATEID';
	const ATTR_STATECODE = 'STATECODE';
	const ATTR_ADMREGID = 'ADMREGID';
	const ATTR_ADMCODE = 'ADMCODE';
	const ATTR_IATACODE = 'IATACODE';
	const ATTR_IKAOCODE = 'IKAOCODE';
	const ATTR_PHONECODE = 'PHONECODE';
	const ATTR_IPCITYID = 'IPCITYID';
	const ATTR_LATITUDE = 'LATITUDE';
	const ATTR_LONGITUDE = 'LONGITUDE';
	const ATTR_WHOCRT = 'WHOCRT';
	const ATTR_WHNCRT = 'WHNCRT';
	const ATTR_WHOUPD = 'WHOUPD';
	const ATTR_WHNUPD = 'WHNUPD';
	const ATTR_AURA_ID = 'AURA_ID';
	const ATTR_SHWINGUIDE = 'SHWINGUIDE';
	const ATTR_YNDXWTHRID = 'YNDXWTHRID';
	const ATTR_GMTSHIFT = 'GMTSHIFT';
	const ATTR_SUMMERSHIFT = 'SUMMERSHIFT';
	const ATTR_RANG = 'RANG';
	const ATTR_NAME = 'NAME';
	const ATTR_GMTSHIFT_CHR = 'GMTSHIFT_CHR';
	const ATTR_LATITUDE_E = 'LATITUDE_E';
	const ATTR_LONGITUDE_E = 'LONGITUDE_E';

	/**
	 * @param $id
	 *
	 * @return $this
	 */
	public static function getTownById($id) {
		$cacheKey = md5('Town::findOne(' . $id . ')');
		$town = Yii::$app->cache->get($cacheKey);
		if (false === $town) {
			$town = DspTowns::findOne(['ID' => $id]);
			Yii::$app->cache->set($cacheKey, $town, 0);
		}

		return $town;
	}

	/**
	 * @param string $name
	 *
	 * @return $this
	 */
	public static function getTownByName($name) {
		$cacheKey = md5('Town::findOne(' . $name . ')');
		$town = Yii::$app->cache->get($cacheKey);
		if (false === $town) {
			$town = DspTowns::findOne(['ENAME' => $name]);
			Yii::$app->cache->set($cacheKey, $town, 0);
		}

		return $town;
	}

	/**
	 * @param string $direction
	 *
	 * @return array|mixed|\yii\db\ActiveRecord[]
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function getTownsToTourCatalog($direction = 'from') {
		$with = 'toursFrom.active';
		if ($direction == 'to') {
			$with = 'toursTo.active';
		}

		$cacheKey = md5(self::class . '.(getTownsToTourCatalog)' . $direction . '.' . Yii::$app->env->getTourZone());
		$rows = Yii::$app->cache->get($cacheKey);
		if (false === $rows) {
			$rows = DspTowns::find()
				->select('DISTINCT(TOWNS.ID), TOWNS.RNAME, TOWNS.ENAME')
				->joinWith($with, false, 'JOIN')
				->orderBy('TOWNS.RNAME')
				->asArray()
				->all();

			Yii::$app->cache->set($cacheKey, $rows, 0, new TagDependency(
				[RefItems::class, RIAd::class, DspTowns::class]
			));
		}

		return $rows;
	}

	public static function find() {
		return new QueryTowns(get_called_class());
	}

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{SNS.TOWNS}}';
	}

	/**
	 * Получение мест для города
	 * @return ActiveQuery
	 */
	public function getPlaces() {
		return $this->hasMany(DspPlaces::class, ['CITYID' => 'ID']);
	}

	/**
	 * Получение города АРР
	 */
	public function getArrCity() {
		return $this->hasOne(ARRCity::class, ['CITYID' => 'ID']);
	}

	/**
	 * Страна города
	 * @return ActiveQuery
	 */
	public function getCountry() {
		return $this->hasOne(DspCountries::class, ['ID' => 'STATEID']);
	}

	public function getToursFrom() {
		return $this->hasMany(RefItems::class, ['ID' => 'ITMID'])
			->viaTable('T3.RI_TOUR_WPS', ['CITYID' => 'ID'])->where('T3.RI_TOUR_WPS.EDGEPOINT = 1');
	}

	public function getToursTo() {
		return $this->hasMany(RefItems::class, ['ID' => 'ITMID'])
			->viaTable('T3.RI_TOUR_WPS', ['CITYID' => 'ID'])->where('T3.RI_TOUR_WPS.DESTPOINT = 1');
	}

	/** Константы городов, которые используются в отображении карточки филиалов для отображения доп услуг */
	const NAKHODKA_ID = '_1CK0R7Z9M';
	const B_KAMEN_ID = '000000138R';
	const PARTIZANSK_ID = '000000138Q';
	const FOKINO_ID = '000000138O';
	const BEREGOVOY_ID = '_1CK0R7W4Z';
	const ST_PETERBURG_ID = '_1CK0R80NZ';


}
