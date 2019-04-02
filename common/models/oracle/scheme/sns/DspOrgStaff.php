<?php

namespace common\models\oracle\scheme\sns;

use common\components\helpers\LArray;
use common\models\oracle\scheme\DspBaseModel;
use Yii;
use yii\caching\TagDependency;

/**
 *
 * @author isakov.v
 *
 * Модель таблицы ORGSTAFF
 *
 * Поля таблицы:
 * @property string                                               $ID
 * @property string                                               $PERSONID
 * @property string                                               $LNAME
 * @property string                                               $FNAME
 * @property string                                               $MNAME
 * @property string                                               $ORGID
 * @property string                                               $DEPID
 * @property string                                               $MNGID
 * @property string                                               $POSTID
 * @property string                                               $POST
 * @property string                                               $POSTTYPE
 * @property string                                               $POSTRIGHTS
 * @property string                                               $ROLE
 * @property string                                               $LOCID
 * @property string                                               $LOCNAME
 * @property string                                               $LOCPHONE
 * @property string                                               $PLACEID
 * @property string                                               $PLACECODE
 * @property string                                               $PHOTOURL
 * @property string                                               $CITYCODE
 * @property string                                               $WRKPHONE
 * @property string                                               $MOBPHONE
 * @property string                                               $HOMPHONE
 * @property string                                               $EMAIL
 * @property int                                                  $ACTIVE
 * @property string                                               $BEGDATE
 * @property string                                               $ENDDATE
 * @property string                                               $REMARKS
 * @property string                                               $WHOCRT
 * @property string                                               $WHNCRT
 * @property string                                               $WHOCHNG
 * @property string                                               $WHNCHNG
 * @property string                                               $BIRTHDATE
 * @property string                                               $BIRTHPLACE
 * @property string                                               $SEX
 * @property int                                                  $EDUCATION
 * @property string                                               $INN
 * @property string                                               $SEQNUM
 * @property string                                               $LABOURTYPE
 * @property string                                               $MARRIAGE
 * @property string                                               $CITIZENSHIP
 * @property string                                               $JOBTYPE
 * @property string                                               $POSTBEGDATE
 * @property int                                                  $STATUSCODE
 * @property string                                               $STATUSNOTE
 * @property string                                               $TABNUM
 * @property string                                               $BOSSREASON
 * @property string                                               $ALTDEPNAME
 * @property int                                                  $NID
 * @property int                                                  $FIXEDLNAME
 * @property int                                                  $AURAID
 * @property string                                               $CALCBEGDATE
 * @property string                                               $PREJOBENDDATE
 * @property string                                               $ADMPLCID
 * @property string                                               $RET2WRKDATE
 * @property string                                               $VACSALDODATE
 * @property string                                               $VACSALDODAYS
 * @property string                                               $WHNUPD
 * @property string                                               $JOBSBSTSTFID
 *
 * @property-read \common\models\oracle\scheme\sns\DspOrgPhones[] $phones
 * @property-read \common\models\oracle\scheme\sns\DspStaffEmails $activeEmails
 */
class DspOrgStaff extends DspBaseModel {

	const ATTR_JOBFRACTION = 'JOBFRACTION';
	const ATTR_ID = 'ID';
	const ATTR_PERSONID = 'PERSONID';
	const ATTR_LNAME = 'LNAME';
	const ATTR_FNAME = 'FNAME';
	const ATTR_MNAME = 'MNAME';
	const ATTR_ORGID = 'ORGID';
	const ATTR_DEPID = 'DEPID';
	const ATTR_MNGID = 'MNGID';
	const ATTR_POSTID = 'POSTID';
	const ATTR_POST = 'POST';
	const ATTR_POSTTYPE = 'POSTTYPE';
	const ATTR_POSTRIGHTS = 'POSTRIGHTS';
	const ATTR_ROLE = 'ROLE';
	const ATTR_LOCID = 'LOCID';
	const ATTR_LOCNAME = 'LOCNAME';
	const ATTR_LOCPHONE = 'LOCPHONE';
	const ATTR_PLACEID = 'PLACEID';
	const ATTR_PLACECODE = 'PLACECODE';
	const ATTR_PHOTOURL = 'PHOTOURL';
	const ATTR_CITYCODE = 'CITYCODE';
	const ATTR_WRKPHONE = 'WRKPHONE';
	const ATTR_MOBPHONE = 'MOBPHONE';
	const ATTR_HOMPHONE = 'HOMPHONE';
	const ATTR_EMAIL = 'EMAIL';
	const ATTR_ACTIVE = 'ACTIVE';
	const ATTR_BEGDATE = 'BEGDATE';
	const ATTR_ENDDATE = 'ENDDATE';
	const ATTR_REMARKS = 'REMARKS';
	const ATTR_WHOCRT = 'WHOCRT';
	const ATTR_WHNCRT = 'WHNCRT';
	const ATTR_WHOCHNG = 'WHOCHNG';
	const ATTR_WHNCHNG = 'WHNCHNG';
	const ATTR_BIRTHDATE = 'BIRTHDATE';
	const ATTR_BIRTHPLACE = 'BIRTHPLACE';
	const ATTR_SEX = 'SEX';
	const ATTR_EDUCATION = 'EDUCATION';
	const ATTR_INN = 'INN';
	const ATTR_SEQNUM = 'SEQNUM';
	const ATTR_LABOURTYPE = 'LABOURTYPE';
	const ATTR_MARRIAGE = 'MARRIAGE';
	const ATTR_CITIZENSHIP = 'CITIZENSHIP';
	const ATTR_JOBTYPE = 'JOBTYPE';
	const ATTR_POSTBEGDATE = 'POSTBEGDATE';
	const ATTR_STATUSCODE = 'STATUSCODE';
	const ATTR_STATUSNOTE = 'STATUSNOTE';
	const ATTR_TABNUM = 'TABNUM';
	const ATTR_BOSSREASON = 'BOSSREASON';
	const ATTR_ALTDEPNAME = 'ALTDEPNAME';
	const ATTR_NID = 'NID';
	const ATTR_FIXEDLNAME = 'FIXEDLNAME';
	const ATTR_AURAID = 'AURAID';
	const ATTR_CALCBEGDATE = 'CALCBEGDATE';
	const ATTR_PREJOBENDDATE = 'PREJOBENDDATE';
	const ATTR_ADMPLCID = 'ADMPLCID';
	const ATTR_RET2WRKDATE = 'RET2WRKDATE';
	const ATTR_VACSALDODATE = 'VACSALDODATE';
	const ATTR_VACSALDODAYS = 'VACSALDODAYS';
	const ATTR_WHNUPD = 'WHNUPD';
	const ATTR_JOBSBSTSTFID = 'JOBSBSTSTFID';
	const ATTR_PRESTAFFID = 'PRESTAFFID';

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{SNS.ORGSTAFF}}';
	}

	/**
	 * Получение email-ов для роли в системе
	 *
	 * @param $sysId
	 * @param $role
	 *
	 * @return array
	 */
	public static function getMailsByRole($sysId, $role) {
		$sql
			= "select distinct e.email
				  from sns.StfEMails e
				  join sns.StfSysRights r
					on r.Staffid = e.Staffid
				   and sysdate between nvl(r.begdt, sysdate) and nvl(r.enddt, sysdate)
				   and r.sysfuncid =
					   (select id
						  from sftsysfuncs sf
						 where sf.sysid = (select NID
											 from sns.Softsystems ss
											where ss.code = '" . strtoupper($sysId) . "')
						   and '/' || role_code || '/' like '%" . $role . "%')
				  join sns.orgstaff os
					on os.id = e.staffid
				   and os.active = 1
				   and os.StatusCode = 1
				 where e.isActive = 1
				   and e.isPublic = 1
				";

		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, $sysId, $role]);
		$rows = Yii::$app->cache->get($cacheKey);

		if (false === $rows) {
			$connection = Yii::$app->getDb();
			$rows = $connection->createCommand($sql)->queryAll();
			Yii::$app->cache->set($cacheKey, $rows, 60 * 60 * 12, new TagDependency([DspOrgStaff::class]));
		}

		$emails = LArray::extract($rows, 'EMAIL');

		return $emails;
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

	/**
	 * Телефоны
	 *
	 * @return mixed
	 */
	public function getPhones() {
		return $this->hasMany(DspOrgPhones::class, ['STAFFID' => 'ID'])
			->where("PHONETYPE IN ('C','F','S') AND HIDEINWEB = 0");
	}

	/**
	 * Телефоны по LOCID
	 *
	 * @return mixed
	 */
	public function getLocPhones() {
		return $this->hasMany(DspOrgPhones::class, ['LOCID' => 'LOCID'])
			->where("PHONETYPE IN ('C','F','S') AND HIDEINWEB = 0");
	}

	/**
	 * Активные Email-адреса
	 *
	 * @return mixed
	 */
	public function getActiveEmails() {
		return $this->hasMany(DspStaffEmails::class, ['STAFFID' => 'ID'])
			->where(DspStaffEmails::tableName() . '.ISACTIVE = 1');
	}
}