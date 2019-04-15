<?php

namespace common\models;

use common\components\SiteModel;
use common\interfaces\LinkedModels;
use common\models\oracle\scheme\sns\DspOrgs;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Поля таблицы:
 * @property integer $id
 * @property string  $old_id
 * @property string  $parent_id
 * @property string  $id_1c
 * @property integer $aura_id
 * @property string  $name
 * @property string  $org_type
 * @property integer $currency_convert_type
 * @property string  $org_form
 * @property string  $inn
 * @property string  $kpp
 * @property string  $okonh
 * @property string  $okpo
 * @property string  $grp
 * @property string  $phone
 * @property string  $fax
 * @property string  $email
 * @property integer $is_supplier
 * @property integer $is_pay_all
 * @property integer $is_demo
 * @property string  $service_fil_id
 * @property string  $end_date
 * @property string  $website
 * @property string  $insert_stamp
 * @property string  $update_stamp
 */
class Org extends SiteModel implements LinkedModels {

	const ATTR_ID = 'id';
	const ATTR_OLD_ID = 'old_id';
	const ATTR_PRE_ID = 'pre_id';
	const ATTR_PARENT_ID = 'parent_id';
	const ATTR_ID_1C = 'id_1c';
	const ATTR_AURA_ID = 'aura_id';
	const ATTR_NAME = 'name';
	const ATTR_ORG_TYPE = 'org_type';
	const ATTR_CURRENCY_CONVERT_TYPE = 'currency_convert_type';
	const ATTR_ORG_FORM = 'org_form';
	const ATTR_INN = 'inn';
	const ATTR_KPP = 'kpp';
	const ATTR_OKONH = 'okonh';
	const ATTR_OKPO = 'okpo';
	const ATTR_GRP = 'grp';
	const ATTR_PHONE = 'phone';
	const ATTR_FAX = 'fax';
	const ATTR_EMAIL = 'email';
	const ATTR_IS_SUPPLIER = 'is_supplier';
	const ATTR_IS_PAY_ALL = 'is_pay_all';
	const ATTR_IS_DEMO = 'is_demo';
	const ATTR_SERVICE_FIL_ID = 'service_fil_id';
	const ATTR_END_DATE = 'end_date';
	const ATTR_WEBSITE = 'website';
	const ATTR_INSERT_STAMP = 'insert_stamp';
	const ATTR_UPDATE_STAMP = 'update_stamp';

	/**
	 * @return string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function tableName() {
		return 'org';
	}

	/**
	 * @return array
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function attributeLabels() {
		return [
			static::ATTR_ID                    => 'id',
			static::ATTR_OLD_ID                => 'old_id',
			static::ATTR_PRE_ID                => 'pre_id',
			static::ATTR_PARENT_ID             => 'parent_id',
			static::ATTR_ID_1C                 => 'id_1c',
			static::ATTR_AURA_ID               => 'aura_id',
			static::ATTR_NAME                  => 'name',
			static::ATTR_ORG_TYPE              => 'org_type',
			static::ATTR_CURRENCY_CONVERT_TYPE => 'currency_convert_type',
			static::ATTR_ORG_FORM              => 'org_form',
			static::ATTR_INN                   => 'inn',
			static::ATTR_KPP                   => 'kpp',
			static::ATTR_OKONH                 => 'okonh',
			static::ATTR_OKPO                  => 'okpo',
			static::ATTR_GRP                   => 'grp',
			static::ATTR_PHONE                 => 'phone',
			static::ATTR_FAX                   => 'fax',
			static::ATTR_EMAIL                 => 'email',
			static::ATTR_IS_SUPPLIER           => 'is_supplier',
			static::ATTR_IS_PAY_ALL            => 'is_pay_all',
			static::ATTR_IS_DEMO               => 'is_demo',
			static::ATTR_SERVICE_FIL_ID        => 'service_fil_id',
			static::ATTR_END_DATE              => 'end_date',
			static::ATTR_WEBSITE               => 'website',
			static::ATTR_INSERT_STAMP          => 'insert_stamp',
			static::ATTR_UPDATE_STAMP          => 'update_stamp',
		];
	}

	/**
	 * @inheritdoc
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function getOldIdField() {
		return static::ATTR_OLD_ID;
	}

	/**
	 * @inheritdoc
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function getLinkedModel() {
		return [
			static::class => DspOrgs::class
		];
	}

	/**
	 * @inheritdoc
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function getInternalInvalidateField() {
		return static::ATTR_UPDATE_STAMP;
	}

	/**
	 * @inheritdoc
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function getOuterInvalidateField() {
		return DspOrgs::ATTR_WHNUPD;
	}

	/**
	 * @inheritdoc
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function getLinkedFields() {
		return [
			DspOrgs::ATTR_ID         => static::ATTR_OLD_ID,
			DspOrgs::ATTR_PREID      => static::ATTR_PRE_ID,
			DspOrgs::ATTR_PARID      => static::ATTR_PARENT_ID,
			DspOrgs::ATTR_ID1C       => static::ATTR_ID_1C,
			DspOrgs::ATTR_IDAURA     => static::ATTR_AURA_ID,
			DspOrgs::ATTR_NAME       => static::ATTR_NAME,
			DspOrgs::ATTR_ORGTYPE    => static::ATTR_ORG_TYPE,
			DspOrgs::ATTR_CRCCNVTYPE => static::ATTR_CURRENCY_CONVERT_TYPE,
			DspOrgs::ATTR_ORGFORM    => static::ATTR_ORG_FORM,
			DspOrgs::ATTR_INN        => static::ATTR_INN,
			DspOrgs::ATTR_KPP        => static::ATTR_KPP,
			DspOrgs::ATTR_OKONH      => static::ATTR_OKONH,
			DspOrgs::ATTR_OKPO       => static::ATTR_OKPO,
			DspOrgs::ATTR_GRP        => static::ATTR_GRP,
			DspOrgs::ATTR_PHONE      => static::ATTR_PHONE,
			DspOrgs::ATTR_FAX        => static::ATTR_FAX,
			DspOrgs::ATTR_EMAIL      => static::ATTR_EMAIL,
			DspOrgs::ATTR_ISSUPPLIER => static::ATTR_IS_SUPPLIER,
			DspOrgs::ATTR_ISPAYALL   => static::ATTR_IS_PAY_ALL,
			DspOrgs::ATTR_ISDEMO     => static::ATTR_IS_DEMO,
			DspOrgs::ATTR_SRVFILID   => static::ATTR_SERVICE_FIL_ID,
			DspOrgs::ATTR_ENDDATE    => static::ATTR_END_DATE,
			DspOrgs::ATTR_WEBSITE    => static::ATTR_WEBSITE,
			DspOrgs::ATTR_WHNCRT     => static::ATTR_INSERT_STAMP,
			DspOrgs::ATTR_WHNUPD     => static::ATTR_UPDATE_STAMP,
		];
	}

	/**
	 * Конвертация данных
	 *
	 * @param string $fieldName
	 * @param mixed  $data
	 *
	 * @return mixed
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function getConvertedField($fieldName, $data) {
		switch ($fieldName) {
			case DspOrgs::ATTR_WHNCRT:
				if (null === $data) {
					return new Expression('sysdate');
				}

				return $data;
				break;
			default:
				return trim($data);
				break;
		}
	}
}
