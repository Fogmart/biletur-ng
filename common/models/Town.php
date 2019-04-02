<?php

namespace common\models;

use common\interfaces\LinkedModels;
use common\models\oracle\scheme\sns\DspTowns;
use yii\db\ActiveRecord;

/**
 * Модель Городов
 *
 * @author isakov.v
 *
 * Поля таблицы:
 * @property int    $id
 * @property int    $old_id                 <= $ID
 * @property string $name                   <= $NAME
 * @property string $r_name                 <= $RNAME
 * @property string $e_name                 <= $ENAME
 * @property string $code                   <= $CODE
 * @property int    $country_id             <= $STATEID
 * @property string $country_code
 * @property double $adm_reg_id
 * @property string $adm_code
 * @property string $iata_code
 * @property string $ikao_code
 * @property string $phone_code
 * @property string $latitude
 * @property string $longitude
 * @property double $aura_id
 * @property double $shwinguide
 * @property string $yandex_weather_id
 * @property double $gmt_shift
 * @property double $rang
 * @property string $insert_stamp           <= $WHNCRT
 * @property string $update_stamp           <= $WHNUPD
 */
class Town extends ActiveRecord implements LinkedModels {

	const ATTR_ID = 'id';
	const ATTR_OLD_ID = 'old_id';
	const ATTR_NAME = 'name';
	const ATTR_R_NAME = 'r_name';
	const ATTR_E_NAME = 'e_name';
	const ATTR_CODE = 'code';
	const ATTR_COUNTRY_ID = 'state_id';
	const ATTR_COUNTRY_CODE = 'state_code';
	const ATTR_ADM_REG_ID = 'adm_reg_id';
	const ATTR_ADM_CODE = 'adm_code';
	const ATTR_IATA_CODE = 'iata_code';
	const ATTR_IKAO_CODE = 'ikao_code';
	const ATTR_PHONE_CODE = 'phone_code';
	const ATTR_LATITUDE = 'latitude';
	const ATTR_LONGITUDE = 'longitude';
	const ATTR_AURA_ID = 'aura_id';
	const ATTR_SHWINGUIDE = 'shwinguide';
	const ATTR_YANDEX_WEATHER_ID = 'yandex_weather_id';
	const ATTR_GMT_SHIFT = 'gmt_shift';
	const ATTR_RANG = 'rang';
	const ATTR_INSERT_STAMP = 'insert_stamp';
	const ATTR_UPDATE_STAMP = 'update_stamp';

	public static function tableName() {
		return '{{%town}}';
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
			static::class => DspTowns::class
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
		return DspTowns::ATTR_WHNUPD;
	}

	/**
	 * @inheritdoc
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function getLinkedFields() {
		return [
			DspTowns::ATTR_ID => static::ATTR_OLD_ID,
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
		/*switch ($fieldName) {
			case DspNews::ATTR_NEWSBANDID:
				if (!array_key_exists($data, static::CATEGORY_LINK)) {
					return 0;
				}

				return static::CATEGORY_LINK[$data];
				break;
			default:
				return $data;
				break;
		}*/
	}
}