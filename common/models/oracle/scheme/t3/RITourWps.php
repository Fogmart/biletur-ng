<?php

namespace common\models\scheme\t3;

use common\models\oracle\scheme\DspBaseModel;
use common\models\oracle\scheme\t3\RefItems;
use common\models\oracle\scheme\t3\RIAd;
use Yii;
use yii\caching\TagDependency;
use yii\db\ActiveQuery;

/**
 * @author isakov.v
 *
 * Модель маршрута туров из схемы T3
 *
 * Поля таблицы:
 *
 * @property int    $ID
 * @property int    $NPP
 * @property string $PLACE
 * @property string $CITYID
 * @property string $CITYCODE
 * @property string $CITY
 * @property string $REGION
 * @property string $COUNTRY
 * @property int    $NDAYS
 * @property string $MEETPLACE
 * @property string $MEETTIME
 * @property string $WHOCRT
 * @property string $WHNCRT
 * @property string $WHOCHNG
 * @property string $WHNCHNG
 * @property int    $EDGEPOINT
 * @property int    $DESTPOINT
 * @property int                                                $ITMID
 * @property string                                             $TRNSFRPOINT
 *
 * @property-read \common\models\oracle\scheme\t3\RefItems  $refItems
 * @property-read \common\models\oracle\scheme\sns\DspTowns $city
 *
 */
class RITourWps extends DspBaseModel {

	/**
	 * Регионы в которых проходят этапы активных туров
	 *
	 * @return array|mixed|\yii\db\ActiveRecord[]
	 */
	public static function getActiveRegions() {
		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, Yii::$app->env->getTourZone()]);
		$rows = Yii::$app->cache->get($cacheKey);
		if (false === $rows) {
			$rows = RITourWps::find()
				->select('DISTINCT(REGION) AS ID')
				->joinWith('refItems.active', false, 'JOIN')
				->asArray()
				->all();
			Yii::$app->cache->set(
				$cacheKey, $rows, 0, new TagDependency(
					[RefItems::class, RIAd::class, RITourWps::tableName()]
				)
			);
		}

		return $rows;
	}

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{T3.RI_TOUR_WPS}}';
	}

	/**
	 * Страны в которых проходят этапы активных туров
	 *
	 * @return array|mixed|\yii\db\ActiveRecord[]
	 */
	public static function getActiveCountries() {
		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, Yii::$app->env->getTourZone()]);
		$rows = Yii::$app->cache->get($cacheKey);
		if (false === $rows) {
			$rows = RITourWps::find()
				->select('DISTINCT(COUNTRY) AS ID')
				->joinWith('refItems.active', false, 'JOIN')
				->asArray()
				->all();

			Yii::$app->cache->set(
				$cacheKey, $rows, 0, new TagDependency(
					[RefItems::class, RIAd::class, RITourWps::class]
				)
			);
		}

		return $rows;
	}

	/**
	 * Периодичность проверки актуальности кеша
	 * @return int
	 */
	public function getInvalidateTime() {
		return 60 * 60 * 1;
	}

	/**
	 * Поле в таблице по которому проверям актуальность
	 * @return string
	 */
	public function getInvalidateField() {
		return 'WHNCHNG';
	}

	/**
	 * Связь этапа с туром
	 *
	 * @return ActiveQuery
	 */
	public function getRefItems() {
		return $this->hasOne(RefItems::class, ['ID' => 'ITMID']);
	}

	/**
	 * Связь этапа со страной
	 * @return \common\models\oracle\scheme\sns\DspTowns
	 */
	public function getCity() {
		return $this->hasOne(DspTowns::class, ['ID' => 'CITYID']);
	}
}