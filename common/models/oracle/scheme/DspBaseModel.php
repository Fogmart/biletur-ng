<?php

namespace common\models\oracle\scheme;

use Yii;
use yii\db\ActiveRecord;

/**
 * Переопределение БД для моделей из старого Оракла
 *
 * @package common\models\oracle\scheme
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class DspBaseModel extends ActiveRecord {
	public static function getDb() {
		return Yii::$app->get('dbDsp');
	}
}