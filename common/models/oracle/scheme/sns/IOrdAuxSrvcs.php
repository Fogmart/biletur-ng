<?php

namespace common\models\scheme\sns;

use common\components\TagDependency;
use common\interfaces\InvalidateModels;
use Yii;
use yii\db\ActiveRecord;

/**
 *
 * @author isakov.v
 *
 * Модель дополнительных сервисов при бронировании авиабилетов
 *
 * Поля таблицы:
 * @property int    $ID
 * @property string $NAME
 * @property int    $ACTIV
 *
 */
class IOrdAuxSrvcs extends ActiveRecord implements InvalidateModels {

	/**
	 * Получение активных сервисов
	 * @return array|mixed|\yii\db\ActiveRecord[]
	 */
	public static function getActive() {
		$cacheKey = md5('IordAuxSrvcs.getActive');
		$services = Yii::$app->memcache->get($cacheKey);
		if (false === $services) {
			$services = self::find()->where('ACTIV = 1')->all();
			Yii::$app->memcache->set($cacheKey, $services, 0, new TagDependency([IOrdAuxSrvcs::tableName()]));
		}

		return $services;
	}

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{SNS.IORD_AUXSRVCS}}';
	}

	/**
	 * Периодичность проверки актуальности кеша
	 * @return int
	 */
	public function getInvalidateTime() {
		return 60 * 60 * 24;
	}

	/**
	 * Поле в таблице по которому проверям актуальность
	 * @return string
	 */
	public function getInvalidateField() {
		return 'ROWNUM';
	}
}