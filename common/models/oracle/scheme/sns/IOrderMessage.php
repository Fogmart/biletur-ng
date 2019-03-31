<?php

namespace common\models\oracle\scheme\sns;

use common\components\BileturActiveRecord;
use common\interfaces\InvalidateModels;

/**
 *
 * @author isakov.v
 *
 * Модель заказов
 *
 * Поля таблицы:
 * @property string $ID
 * @property string $ORDID
 * @property string $WHNCRT
 * @property string $AUTHORNAME
 * @property string $STAFFID
 * @property string $MSG
 * @property int    $ISCOMMENT
 * @property int    $ISOFFER
 * @property int    $ANSWEROK
 * @property string $WHNANSWER
 * @property string $WHNREAD
 *
 */
class IOrderMessage extends BileturActiveRecord implements InvalidateModels {
	public $ordSecret;

	/**
	 * Ручное определение PK т.к. если его нет в таблице то не будут работать методы save() и update()
	 *
	 * @return array|\string[]
	 */
	public static function primaryKey() {
		return ['ID'];
	}

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{SNS.IORDMSG}}';
	}

	/**
	 * Правила валидации полей
	 * @return array
	 */
	public function rules() {
		return [
			[
				[
					'MSG',
					'ORDID',
					'ordSecret'
				],
				'required'
			],
		];
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
		return 'WHNCRT';
	}

	const OFFER_ACTION_CONFIRM = 1;
	const OFFER_ACTION_REJECT = 0;
}