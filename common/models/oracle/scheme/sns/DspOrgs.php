<?php

namespace common\models\oracle\scheme\sns;

use common\models\oracle\scheme\DspBaseModel;

/**
 *
 * @author isakov.v
 *
 * Модель таблицы SNS.ORGS
 *
 * Поля таблицы:
 * @property string                                           $ID
 * @property int                                              $PREID
 * @property int                                              $PARID
 * @property string                                           $ID1C
 * @property string                                           $IDAURA
 * @property string                                           $NAME
 * @property string                                           $ORGTYPE
 * @property int                                              $CRCCNVTYPE
 * @property string                                           $ORGFORM
 * @property string                                           $INN
 * @property string                                           $KPP
 * @property string                                           $OKONH
 * @property string                                           $OKPO
 * @property string                                           $GRP
 * @property string                                           $PHONE
 * @property string                                           $FAX
 * @property string                                           $EMAIL
 * @property int                                              $ISSUPPLIER
 * @property int                                              $ISPAYALL
 * @property int                                              $ISDEMO
 * @property string                                           $SRVFILID
 * @property string                                           $ENDDATE
 * @property string                                           $WHOCRT
 * @property string                                           $WHNCRT
 * @property string                                           $WHOCHNG
 * @property string                                           $WHNCHNG
 * @property string                                           $WEBSITE
 * @property string                                           $WHNUPD
 *
 *
 * @property \common\models\oracle\scheme\sns\DspOrgAddrs $legalAddress
 * @property \common\models\oracle\scheme\sns\DspOrgAddrs $localAddress
 * @property \common\models\oracle\scheme\sns\DspOrgAcnts $account
 * @property \common\models\oracle\scheme\sns\DspOrgDogs  $dogs
 */
class DspOrgs extends DspBaseModel {
	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{SNS.ORGS}}';
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
		return 'WHNUPD';
	}

	/**
	 * @param $ids
	 *
	 * @return \yii\db\ActiveQuery
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function getOrgstaff() {
		return $this->hasMany(DspOrgStaff::class, ['ORGID' => 'ID'])->andWhere(['IN', 'POSTID', $ids]);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function getLegalAddress() {
		return $this->hasOne(DspOrgAddrs::class, [DspOrgAddrs::ATTR_ORGID => 'ID'])
			->andWhere([DspOrgAddrs::ATTR_ADDR_TYPE_ID => 1])
			->andWhere([DspOrgAddrs::ATTR_ACTIVE => 1]);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function getLocalAddress() {
		return $this->hasOne(DspOrgAddrs::class, [DspOrgAddrs::ATTR_ORGID => 'ID'])
			->andWhere([DspOrgAddrs::ATTR_ADDR_TYPE_ID => 2])
			->andWhere([DspOrgAddrs::ATTR_ACTIVE => 1]);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function getAccount() {
		return $this->hasOne(DspOrgAcnts::class, [DspOrgAcnts::ATTR_ORGID => 'ID']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function getDogs() {
		return $this->hasMany(DspOrgDogs::class, [DspOrgDogs::ATTR_ORGID => 'ID'])->orderBy(DspOrgDogs::ATTR_DOG_DATE);
	}
}