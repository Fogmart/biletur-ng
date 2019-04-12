<?php

namespace common\models\oracle\scheme\sns;

use common\components\helpers\OraHelper;
use common\models\oracle\scheme\DspBaseModel;

/**
 *
 * @author isakov.v
 *
 * Модель сообщений
 *
 * Поля таблицы:
 * @property int                                     $ID
 * @property string                                  $SYSID
 * @property string                                  $REFCID
 * @property string                                  $REFNID
 * @property string                                  $MSGDT
 * @property string                                  $STAFFID
 * @property string                                  $WIWID
 * @property string                                  $MSG
 * @property string                                  $RECIPIENTS
 * @property string                                  $PRVID
 * @property string                                  $PARID
 * @property string                                  $PRSN_SRC
 * @property string                                  $PRSN_CID
 * @property string                                  $PRSN_NID
 * @property string                                  $SUBJ
 *
 * @property-read \common\models\scheme\sns\OrgStaff $staffPerson;
 */
class AppMessages extends DspBaseModel {
	public $ordSecret;

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{SNS.APPMSGS}}';
	}

	public function init() {
		parent::init();
		$this->ID = OraHelper::getNextSeqVal('sns.SQ_MSGS');
	}

	/**
	 * Правила валидации полей
	 * @return array
	 */
	public function rules() {
		return [
			[['MSG', 'SYSID', 'REFCID'], 'required'],
			[['ordSecret'], 'required', 'on' => self::SCENARIO_FRONTEND],
		];
	}

	/**
	 * Периодичность проверки актуальности кеша
	 * @return int
	 */
	public function getInvalidateTime() {
		return 60 * 30;
	}

	/**
	 * Поле в таблице по которому проверям актуальность
	 * @return string
	 */
	public function getInvalidateField() {
		return 'ROWNUM';
	}

	/**
	 * Связка с персоналом
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getStaffPerson() {
		return $this->hasOne(DspOrgStaff::class, ['ID' => 'STAFFID']);
	}

	const SCENARIO_FRONTEND = 'fromFrontend';
	const RECIPIENTS_AGENTS = '/A/';
}