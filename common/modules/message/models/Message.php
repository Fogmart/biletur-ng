<?php

namespace common\modules\message\models;

use yii\db\ActiveRecord;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;

/**
 * Поля таблицы:
 * @property integer $id
 * @property string  $object
 * @property string  $object_id
 * @property string  $user_id
 * @property string  $user_name
 * @property string  $message
 * @property string  $insert_stamp
 * @property string  $update_stamp
 */
class Message extends ActiveRecord {

	const ATTR_ID = 'id';
	const ATTR_OBJECT = 'object';
	const ATTR_OBJECT_ID = 'object_id';
	const ATTR_USER_ID = 'user_id';
	const ATTR_USER_NAME = 'user_name';
	const ATTR_MESSAGE = 'message';
	const ATTR_INSERT_STAMP = 'insert_stamp';
	const ATTR_UPDATE_STAMP = 'update_stamp';

	public $isMine = false;

	/**
	 * @return string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function tableName() {
		return 'message';
	}

	/**
	 * @return array
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function attributeLabels() {
		return [
			static::ATTR_ID           => 'id',
			static::ATTR_OBJECT       => 'object',
			static::ATTR_OBJECT_ID    => 'object_id',
			static::ATTR_USER_ID      => 'user_id',
			static::ATTR_USER_NAME    => 'user_name',
			static::ATTR_MESSAGE      => 'Сообщение',
			static::ATTR_INSERT_STAMP => 'insert_stamp',
			static::ATTR_UPDATE_STAMP => 'update_stamp',
		];
	}

	/**
	 * @return array
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function rules() {
		return [
			[static::ATTR_USER_NAME, RequiredValidator::class],
			[static::ATTR_USER_NAME, StringValidator::class, 'max' => 20],
			[static::ATTR_OBJECT, RequiredValidator::class],
			[static::ATTR_OBJECT_ID, RequiredValidator::class],
			[static::ATTR_MESSAGE, RequiredValidator::class],
			[static::ATTR_MESSAGE, StringValidator::class, 'min' => 1],
		];
	}
}
