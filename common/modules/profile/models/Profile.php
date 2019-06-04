<?php

namespace common\modules\profile\models;

use common\components\SiteModel;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * Модель Профиля пользователя
 *
 * @author isakov.v
 *
 * Поля таблицы:
 * @property int    $id
 * @property int    $user_id
 * @property string $email
 *
 */
class Profile extends SiteModel {

	/**
	 * @return array
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function behaviors() {
		return [
			[
				'class'              => TimestampBehavior::class,
				'createdAtAttribute' => 'insert_stamp',
				'updatedAtAttribute' => 'update_stamp',
				'value'              => new Expression('sysdate'),
			],
		];
	}

	public static function tableName() {
		return '{{%profile}}';
	}

	public static function getTitle($id) {
		$labels = self::labels();

		return $labels[$id][Yii::$app->env->getLanguage()];
	}

	public static function labels() {
		return [

		];
	}
}