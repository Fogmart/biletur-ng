<?php

namespace common\modules\profile\models;

use common\components\SiteModel;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * Модель Заказа сайта
 *
 * @author isakov.v
 *
 * Поля таблицы:
 * @property int    $id
 * @property int    $user_id
 * @property string $insert_stamp
 * @property string $update_stamp
 *
 */
class Order extends SiteModel {

	const ATTR_ID = 'id';
	const ATTR_USER_ID = 'user_id';
	const ATTR_INSERT_STAMP = 'insert_stamp';
	const ATTR_UPDATE_STAMP = 'update_stamp';

	const ORDER_TYPE_EXCURSION = 0;
	const ORDER_TYPE_HOTEL = 1;

	const ORDER_TYPE_NAMES = [
		self::ORDER_TYPE_EXCURSION => 'Экскурсия',
		self::ORDER_TYPE_HOTEL     => 'Отель',
	];


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

		];
	}

	/**
	 * @inheritDoc
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function attributeLabels() {
		return [

		];
	}

	public static function tableName() {
		return '{{%order}}';
	}
}