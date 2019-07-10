<?php

namespace common\models\oracle\scheme\t3;

use common\base\helpers\Dump;
use common\models\oracle\scheme\DspBaseModel;
use common\models\oracle\scheme\t3\RefItems;
use common\models\oracle\scheme\t3\RIAd;
use Yii;
use yii\caching\TagDependency;

/**
 * @author isakov.v
 *
 * Модель типов туров из схемы T3
 *
 * Поля таблицы:
 *
 * @property int    $ID
 * @property int    $CODE
 * @property string $NAME
 * @property string $WHOCRT
 * @property string $WHNCRT
 * @property string $WHOUPD
 * @property string $WHNUPD
 * @property string $NAME_EN
 * @property int    $ACTIVE
 */
class TourTypes extends DspBaseModel {

	const ATTR_ID = 'ID';
	const ATTR_NAME = 'NAME';

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{T3.TOURTYPES}}';
	}

	/**
	 * Получение активных типов туров для контрола
	 *
	 * @return array|mixed
	 */
	public static function getActive() {
		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, Yii::$app->env->getTourZone(), 1]);
		$rows = Yii::$app->cache->get($cacheKey);
		if (false === $rows) {
			$rows = TourTypes::find()
				->select('T3.TOURTYPES.ID, T3.TOURTYPES.NAME')
				->joinWith('refItems.active', false, 'JOIN')
				->orderBy('T3.TOURTYPES.ID DESC')

				->all();

			Yii::$app->cache->set(
				$cacheKey, $rows, 0, new TagDependency([
						'tags' =>
							[RefItems::class, TourTypes::class, RIAd::class]
					]
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
		return 'ROWNUM';
	}

	public function getRefItems() {
		return $this->hasMany(RefItems::class, ['ID' => 'ITMID'])
			->viaTable('T3.RI_TOUR_TYPES', ['TOURTYPEID' => 'ID']);
	}
}