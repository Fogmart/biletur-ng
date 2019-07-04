<?php

namespace common\models\oracle\scheme\t3;

use common\models\Country;
use common\models\oracle\scheme\DspBaseModel;
use common\models\oracle\scheme\sns\DspTowns;
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
 * @property int                                            $ID
 * @property int                                            $NPP
 * @property string                                         $PLACE
 * @property string                                         $CITYID
 * @property string                                         $CITYCODE
 * @property string                                         $CITY
 * @property string                                         $REGION
 * @property string                                         $COUNTRY
 * @property int                                            $NDAYS
 * @property string                                         $MEETPLACE
 * @property string                                         $MEETTIME
 * @property string                                         $WHOCRT
 * @property string                                         $WHNCRT
 * @property string                                         $WHOCHNG
 * @property string                                         $WHNCHNG
 * @property int                                            $EDGEPOINT
 * @property int                                            $DESTPOINT
 * @property int                                            $ITMID
 * @property string                                         $TRNSFRPOINT
 *
 * @property-read \common\models\oracle\scheme\t3\RefItems  $refItems
 * @property-read \common\models\oracle\scheme\sns\DspTowns $city
 *
 */
class RITourWps extends DspBaseModel {
	const ATTR_ITEM_ID = 'ITMID';
	const ATTR_CITY_ID = 'CITYID';
	const ATTR_COUNTRY = 'COUNTRY';
	const ATTR_DESTINATION_POINT = 'DESTPOINT';
	const ATTR_NUMBER = 'NPP';

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{T3.RI_TOUR_WPS}}';
	}

	/**
	 * Регионы в которых проходят этапы активных туров
	 *
	 * @return array|mixed|\yii\db\ActiveRecord[]
	 */
	public static function getActiveRegions() {
		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, Yii::$app->env->getTourZone(), 18]);
		$places = Yii::$app->cache->get($cacheKey);
		if (false === $places) {
			$places = [];

			/** @var static[] $rows */
			$rows = static::find()
				->joinWith(static::REL_REF_ITEMS . '.' . RefItems::REL_ACTIVE, false, 'INNER JOIN')
				->all();

			foreach ($rows as $row) {
				if (empty($row->COUNTRY) || empty($row->CITYID)) {
					continue;
				}

				$places[$row->COUNTRY][$row->CITYID] = $row->CITY;
			}

			ksort($places);
			foreach ($places as &$cities) {
				ksort($cities);
			}

			unset($cities);

			Yii::$app->cache->set(
				$cacheKey, $places, 0, new TagDependency([
						'tags' =>
							[RefItems::class, RIAd::class, RITourWps::tableName()]
					]
				)
			);
		}

		return $places;
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
		return $this->hasOne(RefItems::class, ['ID' => static::ATTR_ITEM_ID]);
	}

	const REL_REF_ITEMS = 'refItems';

	/**
	 * Связь этапа с городом
	 * @return \common\models\oracle\scheme\sns\DspTowns
	 */
	public function getCity() {
		return $this->hasOne(DspTowns::class, ['ID' => 'CITYID']);
	}

	const REL_CITY = 'city';

	/**
	 *
	 * @return \common\models\Country|null
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function getCountry() {
		if (null === $this->city) {
			return null;
		}
		return Country::findOne([Country::ATTR_OLD_ID => $this->city->STATEID]);
	}

	/**
	 * @return string|null
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function getFlagImage() {
		$country = $this->getCountry();

		if (null !== $country) {
			return $country->getFlagImage();
		}

		return null;
	}
}