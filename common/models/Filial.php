<?php

namespace common\models;

use common\components\SiteModel;
use common\interfaces\LinkedModels;
use common\models\oracle\scheme\sns\DspFilials;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Поля таблицы:
 * @property double $id
 * @property string $old_id
 * @property string $filial_code
 * @property string $aura_code
 * @property string $name
 * @property double $org_id
 * @property string $group
 * @property double $boss_id
 * @property string $boss_name
 * @property double $rang
 * @property double $region
 * @property string $beg_date
 * @property string $end_date
 * @property string $insert_stamp
 * @property string $update_stamp
 */
class Filial extends SiteModel implements LinkedModels {

	const ATTR_ID = 'id';
	const ATTR_OLD_ID = 'old_id';
	const ATTR_FILIAL_CODE = 'filial_code';
	const ATTR_AURA_CODE = 'aura_code';
	const ATTR_NAME = 'name';
	const ATTR_ORG_ID = 'org_id';
	const ATTR_GROUP = 'group';
	const ATTR_BOSS_ID = 'boss_id';
	const ATTR_BOSS_NAME = 'boss_name';
	const ATTR_RANG = 'rang';
	const ATTR_REGION = 'region';
	const ATTR_BEG_DATE = 'beg_date';
	const ATTR_END_DATE = 'end_date';
	const ATTR_INSERT_STAMP = 'insert_stamp';
	const ATTR_UPDATE_STAMP = 'update_stamp';

	/**
	 * @inheritDoc
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function tableName() {
		return '{{%filial}}';
	}

	/**
	 * @inheritDoc
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function attributeLabels() {
		return [
			static::ATTR_ID           => 'id',
			static::ATTR_OLD_ID       => 'old_id',
			static::ATTR_FILIAL_CODE  => 'filial_code',
			static::ATTR_AURA_CODE    => 'aura_code',
			static::ATTR_NAME         => 'name',
			static::ATTR_ORG_ID       => 'org_id',
			static::ATTR_GROUP        => 'group',
			static::ATTR_BOSS_ID      => 'boss_id',
			static::ATTR_BOSS_NAME    => 'boss_name',
			static::ATTR_RANG         => 'rang',
			static::ATTR_REGION       => 'region',
			static::ATTR_BEG_DATE     => 'beg_date',
			static::ATTR_END_DATE     => 'end_date',
			static::ATTR_INSERT_STAMP => 'insert_stamp',
			static::ATTR_UPDATE_STAMP => 'update_stamp',
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
			static::class => DspFilials::class
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
		return DspFilials::ATTR_WHNUPD;
	}

	/**
	 * @inheritdoc
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function getLinkedFields() {
		return [
			DspFilials::ATTR_ID       => static::ATTR_OLD_ID,
			DspFilials::ATTR_FILIAL   => static::ATTR_FILIAL_CODE,
			DspFilials::ATTR_AURCODE  => static::ATTR_AURA_CODE,
			DspFilials::ATTR_NAME     => static::ATTR_NAME,
			DspFilials::ATTR_ORGID    => static::ATTR_ORG_ID,
			DspFilials::ATTR_GROUPS   => static::ATTR_GROUP,
			DspFilials::ATTR_BEGDATE  => static::ATTR_END_DATE,
			DspFilials::ATTR_BOSSID   => static::ATTR_BOSS_ID,
			DspFilials::ATTR_BOSSNAME => static::ATTR_BOSS_NAME,
			DspFilials::ATTR_RANG     => static::ATTR_RANG,
			DspFilials::ATTR_REGION   => static::ATTR_REGION,
			DspFilials::ATTR_WHNCRT   => static::ATTR_INSERT_STAMP,
			DspFilials::ATTR_WHNUPD   => static::ATTR_UPDATE_STAMP,
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
			case DspFilials::ATTR_WHNCRT:
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
