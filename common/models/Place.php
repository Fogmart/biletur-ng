<?php
namespace common\models;

use common\interfaces\LinkedModels;
use common\models\oracle\scheme\sns\DspPlaces;
use yii\db\ActiveRecord;

/**
 * Поля таблицы:
 * @property double $id
 * @property string $old_id
 * @property string $id1c
 * @property double $aura_id
 * @property string $list_aura_id
 * @property string $sale_place
 * @property string $name
 * @property double $filial_id
 * @property double $town_id
 * @property string $town_code
 * @property double $loc_id
 * @property string $loc_name
 * @property double $office_manager_id
 * @property string $email
 * @property double $rang
 * @property double $is_filial_hq
 * @property double $is_hide_frm_web
 * @property double $is_published
 * @property double $is_eorder
 * @property double $is_sale_iata
 * @property double $is_sale_avia
 * @property double $is_sale_rail_road
 * @property double $is_sale_tour
 * @property double $is_sale_paper
 * @property double $is_sale_sputnik
 * @property string $address
 * @property string $description_url
 * @property string $credit_cards
 * @property string $placement
 * @property string $route_info
 * @property double $bp_qty
 * @property double $ndsirp_qty
 * @property string $tkp_code
 * @property string $rrsup_org_id
 * @property string $nn_validator
 * @property string $su_validator
 * @property string $pcc
 * @property string $amadeus_office_id
 * @property string $insert_stamp
 * @property string $update_stamp
 */
class Place extends ActiveRecord  implements LinkedModels {

	const ATTR_ID = 'id';
	const ATTR_OLD_ID = 'old_id';
	const ATTR_ID1C = 'id1c';
	const ATTR_AURA_ID = 'aura_id';
	const ATTR_LIST_AURA_ID = 'list_aura_id';
	const ATTR_SALE_PLACE = 'sale_place';
	const ATTR_NAME = 'name';
	const ATTR_FILIAL_ID = 'filial_id';
	const ATTR_TOWN_ID = 'town_id';
	const ATTR_TOWN_CODE = 'town_code';
	const ATTR_LOC_ID = 'loc_id';
	const ATTR_LOC_NAME = 'loc_name';
	const ATTR_OFFICE_MANAGER_ID = 'office_manager_id';
	const ATTR_EMAIL = 'email';
	const ATTR_RANG = 'rang';
	const ATTR_IS_FILIAL_HQ = 'is_filial_hq';
	const ATTR_IS_HIDE_FRM_WEB = 'is_hide_frm_web';
	const ATTR_IS_PUBLISHED = 'is_published';
	const ATTR_IS_EORDER = 'is_eorder';
	const ATTR_IS_SALE_IATA = 'is_sale_iata';
	const ATTR_IS_SALE_AVIA = 'is_sale_avia';
	const ATTR_IS_SALE_RAIL_ROAD = 'is_sale_rail_road';
	const ATTR_IS_SALE_TOUR = 'is_sale_tour';
	const ATTR_IS_SALE_PAPER = 'is_sale_paper';
	const ATTR_IS_SALE_SPUTNIK = 'is_sale_sputnik';
	const ATTR_ADDRESS = 'address';
	const ATTR_DESCRIPTION_URL = 'description_url';
	const ATTR_CREDIT_CARDS = 'credit_cards';
	const ATTR_PLACEMENT = 'placement';
	const ATTR_ROUTE_INFO = 'route_info';
	const ATTR_BP_QTY = 'bp_qty';
	const ATTR_NDSIRP_QTY = 'ndsirp_qty';
	const ATTR_TKP_CODE = 'tkp_code';
	const ATTR_RRSUP_ORG_ID = 'rrsup_org_id';
	const ATTR_NN_VALIDATOR = 'nn_validator';
	const ATTR_SU_VALIDATOR = 'su_validator';
	const ATTR_PCC = 'pcc';
	const ATTR_AMADEUS_OFFICE_ID = 'amadeus_office_id';
	const ATTR_INSERT_STAMP = 'insert_stamp';
	const ATTR_UPDATE_STAMP = 'update_stamp';


	public static function tableName() {
		return '{{%place}}';
	}

	public function attributeLabels() {
		return [
			static::ATTR_ID                => 'id',
			static::ATTR_OLD_ID            => 'old_id',
			static::ATTR_ID1C              => 'id1c',
			static::ATTR_AURA_ID           => 'aura_id',
			static::ATTR_LIST_AURA_ID      => 'list_aura_id',
			static::ATTR_SALE_PLACE        => 'sale_place',
			static::ATTR_NAME              => 'name',
			static::ATTR_FILIAL_ID         => 'filial_id',
			static::ATTR_TOWN_ID           => 'town_id',
			static::ATTR_TOWN_CODE         => 'town_code',
			static::ATTR_LOC_ID            => 'loc_id',
			static::ATTR_LOC_NAME          => 'loc_name',
			static::ATTR_OFFICE_MANAGER_ID => 'office_manager_id',
			static::ATTR_EMAIL             => 'email',
			static::ATTR_RANG              => 'rang',
			static::ATTR_IS_FILIAL_HQ      => 'is_filial_hq',
			static::ATTR_IS_HIDE_FRM_WEB   => 'is_hide_frm_web',
			static::ATTR_IS_PUBLISHED      => 'is_published',
			static::ATTR_IS_EORDER         => 'is_eorder',
			static::ATTR_IS_SALE_IATA      => 'is_sale_iata',
			static::ATTR_IS_SALE_AVIA      => 'is_sale_avia',
			static::ATTR_IS_SALE_RAIL_ROAD => 'is_sale_rail_road',
			static::ATTR_IS_SALE_TOUR      => 'is_sale_tour',
			static::ATTR_IS_SALE_PAPER     => 'is_sale_paper',
			static::ATTR_IS_SALE_SPUTNIK   => 'is_sale_sputnik',
			static::ATTR_ADDRESS           => 'address',
			static::ATTR_DESCRIPTION_URL   => 'description_url',
			static::ATTR_CREDIT_CARDS      => 'credit_cards',
			static::ATTR_PLACEMENT         => 'placement',
			static::ATTR_ROUTE_INFO        => 'route_info',
			static::ATTR_BP_QTY            => 'bp_qty',
			static::ATTR_NDSIRP_QTY        => 'ndsirp_qty',
			static::ATTR_TKP_CODE          => 'tkp_code',
			static::ATTR_RRSUP_ORG_ID      => 'rrsup_org_id',
			static::ATTR_NN_VALIDATOR      => 'nn_validator',
			static::ATTR_SU_VALIDATOR      => 'su_validator',
			static::ATTR_PCC               => 'pcc',
			static::ATTR_AMADEUS_OFFICE_ID => 'amadeus_office_id',
			static::ATTR_INSERT_STAMP      => 'insert_stamp',
			static::ATTR_UPDATE_STAMP      => 'update_stamp',
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
			static::class => DspPlaces::class
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
		return DspPlaces::ATTR_WHNUPD;
	}

	/**
	 * @inheritdoc
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function getLinkedFields() {
		return [
			DspPlaces::ATTR_ID => static::ATTR_OLD_ID,
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
