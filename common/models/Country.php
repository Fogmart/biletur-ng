<?php

namespace common\models;

use common\interfaces\LinkedModels;
use common\models\oracle\scheme\sns\DspCountries;
use common\models\oracle\scheme\sns\DspTowns;
use yii\db\ActiveRecord;

/**
 * Модель Стран
 *
 * @author isakov.v
 *
 * Поля таблицы:
 * @property int    $id
 * @property int    $old_id            <= $ID
 * @property string $name
 * @property string $r_name
 * @property string $e_name
 * @property string $code
 * @property double $aura_id
 * @property double $shwinguide
 * @property string $yandex_weather_id
 * @property string $flag_image
 * @property string $insert_stamp      <= $WHNCRT
 * @property string $update_stamp      <= $WHNCHNG
 */
class Country extends ActiveRecord implements LinkedModels {

	const ATTR_ID = 'id';
	const ATTR_OLD_ID = 'old_id';
	const ATTR_NAME = 'name';
	const ATTR_R_NAME = 'r_name';
	const ATTR_E_NAME = 'e_name';
	const ATTR_CODE = 'code';
	const ATTR_AURA_ID = 'aura_id';
	const ATTR_SHWINGUIDE = 'shwinguide';
	const ATTR_YANDEX_WEATHER_ID = 'yandex_weather_id';
	const ATTR_FLAG_IMAGE = 'flag_image';
	const ATTR_INSERT_STAMP = 'insert_stamp';
	const ATTR_UPDATE_STAMP = 'update_stamp';

	public static function tableName() {
		return '{{%country}}';
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
			static::class => DspCountries::class
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
		return DspCountries::ATTR_WHNUPD;
	}

	/**
	 * @inheritdoc
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function getLinkedFields() {
		return [
			DspCountries::ATTR_ID => static::ATTR_OLD_ID,
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