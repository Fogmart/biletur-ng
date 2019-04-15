<?php

namespace common\modules\news\models;

use common\components\SiteModel;
use common\interfaces\ILinkedModels;
use common\models\oracle\scheme\sns\DspNews;
use Yii;
use yii\db\ActiveRecord;

/**
 * Модель Новостей
 *
 * @author isakov.v
 *
 * Поля таблицы:
 * @property int    $id
 * @property int    $old_id            <= $ID
 * @property string $category_id       <= converted $NEWSBANDID
 * @property string $date              <= $NEWSDATE
 * @property string $title             <= $TITLE
 * @property string $text              <= $MESSAGE
 * @property bool   $is_published      <= $VISIBLE
 * @property bool   $is_hot            <= $HOT
 * @property string $image             <= $IMGURL
 * @property string $lang              <= $LANGCODE
 * @property string $insert_stamp      <= $WHNCRT
 * @property string $update_stamp      <= $WHNCHNG
 */
class News extends SiteModel implements ILinkedModels {

	public static function tableName() {
		return '{{%news}}';
	}

	public static function getTitle($id) {
		$labels = self::labels();

		return $labels[$id][Yii::$app->env->getLanguage()];
	}

	public static function labels() {
		return [
			self::AVIASALE_NEWS      => ['ru' => 'Распродажи авиабилетов', 'en' => 'Sell tickets'],
			self::PASSENGER_NEWS     => ['ru' => 'Новости пассажирам', 'en' => 'News passengers'],
			self::TOURIST_NEWS       => ['ru' => 'Новости туристам', 'en' => 'News tourists'],
			self::AGENCY_NEWS        => ['ru' => 'Новости агентства', 'en' => 'News agency'],
			self::LOCAL_NEWS         => ['ru' => 'Местные новости', 'en' => 'Local news'],
			self::LOCAL_INVOICE_NEWS => ['ru' => 'Местные новости: счета', 'en' => 'Local news: invoices'],
			self::LOCAL_ARR_NEWS     => ['ru' => 'Местные новости: ARR', 'en' => 'Local news: ARR'],
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
			static::class => DspNews::class
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
		return DspNews::ATTR_WHNCHNG;
	}

	/**
	 * @inheritdoc
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function getLinkedFields() {
		return [
			static::ATTR_OLD_ID       => DspNews::ATTR_ID,
			static::ATTR_CATEGORY_ID  => DspNews::ATTR_NEWSBANDID,
			static::ATTR_DATE         => DspNews::ATTR_NEWSDATE,
			static::ATTR_TITLE        => DspNews::ATTR_TITLE,
			static::ATTR_TEXT         => DspNews::ATTR_MESSAGE,
			static::ATTR_IS_PUBLISHED => DspNews::ATTR_VISIBLE,
			static::ATTR_IS_HOT       => DspNews::ATTR_HOT,
			static::ATTR_LANG         => DspNews::ATTR_LANGCODE,
			static::ATTR_INSERT_STAMP => DspNews::ATTR_WHNCRT,
			static::ATTR_UPDATE_STAMP => DspNews::ATTR_WHNCHNG,
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
			case DspNews::ATTR_NEWSBANDID:
				if (!array_key_exists($data, static::CATEGORY_LINK)) {
					return 0;
				}

				return static::CATEGORY_LINK[$data];
				break;
			case DspNews::ATTR_TITLE:
				if (empty($data)) {
					return ' ';
				}

				return trim($data);
				break;
			case DspNews::ATTR_MESSAGE:
				if (empty($data)) {
					return ' ';
				}

				return trim($data);
				break;
			case DspNews::ATTR_LANGCODE:
				if (empty($data)) {
					return 'ru';
				}

				if ((int)$data == 0) {
					return 'ru';
				}

				return trim($data);
				break;
			case DspNews::ATTR_HOT:
			case DspNews::ATTR_VISIBLE:
				if (empty($data)) {
					return 0;
				}

				return trim($data);
				break;
			default:
				return trim($data);
				break;
		}
	}

	const ATTR_ID = 'id';
	const ATTR_OLD_ID = 'old_id';
	const ATTR_CATEGORY_ID = 'category_id';
	const ATTR_DATE = 'date';
	const ATTR_TITLE = 'title';      //0000000001
	const ATTR_TEXT = 'text';     //0000000002
	const ATTR_IS_PUBLISHED = 'is_published';       //0000000003
	const ATTR_IS_HOT = 'is_hot';        //000000000A
	const ATTR_IMAGE = 'image';         //0000000005
	const ATTR_LANG = 'lang'; //0000000006
	const ATTR_INSERT_STAMP = 'insert_stamp';     //0000000007

	//Соответствие категорий старого сайта и нового
	const ATTR_UPDATE_STAMP = 'update_stamp';

	//Категории для публичного сайта
	const AVIASALE_NEWS = 1;
	const PASSENGER_NEWS = 2;
	const TOURIST_NEWS = 3;
	const AGENCY_NEWS = 4;
	const LOCAL_NEWS = 5;
	const LOCAL_INVOICE_NEWS = 6;
	const LOCAL_ARR_NEWS = 7;
	const LOCAL_OTHER_NEWS = 8;

	const CATEGORY_LINK = [
		'0000000001' => self::AVIASALE_NEWS,
		'0000000002' => self::PASSENGER_NEWS,
		'0000000003' => self::TOURIST_NEWS,
		'000000000A' => self::AGENCY_NEWS,
		'0000000005' => self::LOCAL_NEWS,
		'0000000006' => self::LOCAL_INVOICE_NEWS,
		'0000000007' => self::LOCAL_ARR_NEWS,
		'000000000B' => self::LOCAL_OTHER_NEWS
	];

	const PUBLIC_CATEGORY = [
		self::AVIASALE_NEWS,
		self::PASSENGER_NEWS,
		self::TOURIST_NEWS,
		self::AGENCY_NEWS,
	];
}