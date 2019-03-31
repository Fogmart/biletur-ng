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
 * @property string                                   $ID
 * @property string                                   $RNAME
 * @property string                                   $ENAME
 * @property string                                   $CODE
 * @property string                                   $STATEID
 * @property string                                   $STATECODE
 * @property string                                   $ADMREGID
 * @property string                                   $ADMCODE
 * @property string                                   $IATACODE
 * @property string                                   $IKAOCODE
 * @property string                                   $PHONECODE
 * @property int                                      $IPCITYID
 * @property int                                      $LATITUDE
 * @property int                                      $LONGITUDE
 * @property string                                   $WHOCRT
 * @property string                                   $WHNCRT
 * @property string                                   $WHOUPD
 * @property string                                   $WHNUPD
 * @property int                                      $AURA_ID
 * @property int                                      $SHWINGUIDE
 * @property string                                   $YNDXWTHRID
 * @property int                                      $GMTSHIFT
 * @property int                                      $SUMMERSHIFT
 * @property int                                      $RANG
 * @property string                                   $NAME
 *
 * @property-read \common\models\scheme\arr\ARRCity   $arrCity
 * @property-read \common\models\scheme\sns\Places[]  $places
 * @property-read \common\models\scheme\sns\Countries $country
 */
class DspTowns extends ActiveRecord {

	/**
	 * @param $id
	 *
	 * @return $this
	 */
	public static function getTownById($id) {
		$cacheKey = md5('Town::findOne(' . $id . ')');
		$town = Yii::$app->memcache->get($cacheKey);
		if (false === $town) {
			$town = DspTowns::findOne(['ID' => $id]);
			Yii::$app->memcache->set($cacheKey, $town, 0);
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
		$town = Yii::$app->memcache->get($cacheKey);
		if (false === $town) {
			$town = DspTowns::findOne(['ENAME' => $name]);
			Yii::$app->memcache->set($cacheKey, $town, 0);
		}

		return $town;
	}

	public static function getTownsToTourCatalog($direction = 'from') {
		$with = 'toursFrom.active';
		if ($direction == 'to') {
			$with = 'toursTo.active';
		}
		$cacheKey = md5(self::className() . '.(getTownsToTourCatalog)' . $direction . '.' . Yii::$app->env->getTourZone());
		$rows = Yii::$app->cache->get($cacheKey);
		if (false === $rows) {
			$rows = DspTowns::find()
				->select('DISTINCT(TOWNS.ID), TOWNS.RNAME, TOWNS.ENAME')
				->joinWith($with, false, 'JOIN')
				->orderBy('TOWNS.RNAME')
				->asArray()
				->all();
			Yii::$app->memcache->set($cacheKey, $rows, 0, new TagDependency(
				[RefItems::tableName(), RIAd::tableName(), DspTowns::tableName()]
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
		return $this->hasMany(Places::className(), ['CITYID' => 'ID']);
	}

	/**
	 * Получение города АРР
	 */
	public function getArrCity() {
		return $this->hasOne(ARRCity::className(), ['CITYID' => 'ID']);
	}

	/**
	 * Страна города
	 * @return ActiveQuery
	 */
	public function getCountry() {
		return $this->hasOne(Countries::className(), ['ID' => 'STATEID']);
	}

	public function getToursFrom() {
		return $this->hasMany(RefItems::className(), ['ID' => 'ITMID'])
			->viaTable('T3.RI_TOUR_WPS', ['CITYID' => 'ID'])->where('T3.RI_TOUR_WPS.EDGEPOINT = 1');
	}

	public function getToursTo() {
		return $this->hasMany(RefItems::className(), ['ID' => 'ITMID'])
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
