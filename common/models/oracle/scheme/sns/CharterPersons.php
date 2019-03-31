<?php

namespace common\models\oracle\scheme\sns;

use common\components\helpers\OraHelper;
use common\interfaces\InvalidateModels;
use yii\db\ActiveRecord;

/**
 *
 * @author isakov.v
 *
 * Модель пассажиров чартеров
 *
 * Поля таблицы:
 * @property int    $ID
 * @property string $FIO
 * @property string $WHOCRT
 * @property string $WHNCRT
 * @property string $WHOUPD
 * @property string $WHNUPD
 * @property string $WIWID
 * @property string $STAFFID
 * @property int    $CHRTID
 * @property string $DSP_ID
 * @property string $DSP_CONNID
 * @property string $ORGID
 * @property string $LNAME
 * @property string $FNAME
 * @property string $MNAME
 * @property string $SEX
 * @property string $BIRTHDATE
 * @property string $PHONE
 * @property string $PSP_SERIA
 * @property string $PSP_NUM
 * @property string $PSP_EXPDATE
 * @property int    $SECTQTY
 * @property string $PLNPAYSUM
 * @property string $FCTPAYSUM
 * @property string $LNAME_EN
 * @property string $FNAME_EN
 * @property int    $WARNINGS
 * @property string $COMMTYPEID
 * @property string $TOURORDNUM
 * @property string $TOURORDID
 * @property string $ORDNUM2
 * @property string $REMARKS
 * @property int    $ORDID
 * @property string $PRNTID
 *
 */
class CharterPersons extends ActiveRecord implements InvalidateModels {

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{SNS.CHRT_PRSNS}}';
	}

	public function init() {
		parent::init();
		$this->ID = OraHelper::getNextSeqVal('SQ_Charters');
	}

	public function rules() {
		return [
			[['PSP_SERIA', 'PSP_NUM', 'PSP_EXPDATE', 'LNAME_EN', 'FNAME_EN'], 'required'],
			['PSP_SERIA', 'string', 'min' => 1, 'max' => 5],
			['PSP_NUM', 'string', 'min' => 1, 'max' => 10],
			['PSP_EXPDATE', 'date', 'format' => 'dd-mm-yyyy'],
			['LNAME_EN', 'safe'],
			['FNAME_EN', 'safe'],
			['BIRTHDATE', 'date', 'format' => 'dd-mm-yyyy'],
			['SEX', 'in', 'range' => ['F', 'M']],
		];
	}

	public function attributeLabels() {
		return [
			'PSP_SERIA'   => 'Серия паспорта',
			'PSP_NUM'     => 'Номер паспорта',
			'PSP_EXPDATE' => 'Срок действия',
			'LNAME_EN'    => 'Фамилия по документу',
			'FNAME_EN'    => 'Имя по документу',
			'BIRTHDATE'   => 'Дата рождения',
			'SEX'         => 'Пол',
		];
	}

	/**
	 * Периодичность проверки актуальности кеша
	 * @return int
	 */
	public function getInvalidateTime() {
		return 60 * 60 * 5;
	}

	/**
	 * Поле в таблице по которому проверям актуальность
	 * @return string
	 */
	public function getInvalidateField() {
		return 'WHNUPD';
	}

}