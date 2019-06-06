<?php

namespace common\models;

use common\components\SiteModel;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * Модель лога запросов
 *
 * Столбцы в таблице:
 * @property int     $id             Уникальный идентификатор
 * @property  string $insert_stamp
 */
class LogRequest extends SiteModel {
	const ATTR_ID = 'id';

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
				'updatedAtAttribute' => 'insert_stamp',
				'value'              => new Expression('sysdate'),
			],
		];
	}

	/**
	 * @return string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function tableName() {
		return '{{%log_request}}';
	}

	/**
	 * @inheritdoc
	 *
	 *
	 */
	public function attributeLabels() {
		return [
			static::ATTR_ID => 'Идентификатор',
		];
	}
}