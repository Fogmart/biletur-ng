<?php

namespace common\modules\profile\models;

use common\components\SiteModel;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\validators\EmailValidator;
use yii\validators\StringValidator;

/**
 * Модель Профиля пользователя
 *
 * @author isakov.v
 *
 * Поля таблицы:
 * @property int    $id
 * @property int    $user_id
 * @property string $email
 * @property string $f_name
 * @property string $s_name
 * @property string $l_name
 * @property string $phone
 * @property string $dob
 * @property string $city_id
 * @property string $insert_stamp
 * @property string $update_stamp
 *
 */
class Profile extends SiteModel {

	const ATTR_ID = 'id';
	const ATTR_USER_ID = 'user_id';
	const ATTR_EMAIL = 'email';
	const ATTR_F_NAME = 'f_name';
	const ATTR_S_NAME = 's_name';
	const ATTR_L_NAME = 'l_name';
	const ATTR_PHONE = 'phone';
	const ATTR_DOB = 'dob';
	const ATTR_CITY_ID = 'city_id';
	const ATTR_INSERT_STAMP = 'insert_stamp';
	const ATTR_UPDATE_STAMP = 'update_stamp';

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

	/**
	 * @inheritDoc
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function rules() {
		return [
			[static::ATTR_EMAIL, EmailValidator::class],
			[static::ATTR_F_NAME, StringValidator::class],
			[static::ATTR_S_NAME, StringValidator::class],
			[static::ATTR_L_NAME, StringValidator::class],
			[static::ATTR_PHONE, StringValidator::class],
			[static::ATTR_DOB, StringValidator::class],
			[static::ATTR_CITY_ID, StringValidator::class],
		];
	}

	/**
	 * @inheritDoc
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function attributeLabels() {
		return [
			static::ATTR_EMAIL   => 'Email',
			static::ATTR_F_NAME  => 'Имя',
			static::ATTR_S_NAME  => 'Отчество',
			static::ATTR_L_NAME  => 'Фамилия',
			static::ATTR_PHONE   => 'Телефон',
			static::ATTR_DOB     => 'День рождения',
			static::ATTR_CITY_ID => 'Город',
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