<?php

namespace common\models\oracle\scheme\sns;

use common\interfaces\InvalidateModels;
use common\models\oracle\scheme\DspBaseModel;
use Yii;

/**
 * Модель Ноостей
 *
 * @author isakov.v
 *
 * Модель городов
 *
 * Поля таблицы:
 * @property int    $ID
 * @property string $CID
 * @property string $NEWSBANDID
 * @property string $NEWSDATE
 * @property string $CATEGORY
 * @property string $TITLE
 * @property string $ANONS
 * @property string $MESSAGE
 * @property int    $VISIBLE
 * @property int    $HOT
 * @property int    $SENDREADY
 * @property string $IMGURL
 * @property string $IMGDESCR
 * @property string $IMGLINK
 * @property string $LANGUAGE
 * @property string $WHNCRT
 * @property string $WHOCRT
 * @property string $WHNCHNG
 * @property string $WHOCHNG
 * @property string $ISHTML
 * @property string $LANGCODE
 */
class DspNews extends DspBaseModel implements InvalidateModels {

	/**
	 * @return string
	 */
	public static function tableName() {
		return '{{SNS.NEWS}}';
	}

	public static function getTitle($id) {
		$labels = self::labels();

		return $labels[$id][Yii::$app->env->getLanguage()];
	}

	public static function labels() {
		return [
			self::AVIASALE_NEWS  => ['ru' => 'Распродажи авиабилетов', 'en' => 'Sell tickets'],
			self::PASSANGER_NEWS => ['ru' => 'Новости пассажирам', 'en' => 'DspNews passengers'],
			self::TOURIST_NEWS   => ['ru' => 'Новости туристам', 'en' => 'DspNews tourists'],
			self::AGENCY_NEWS    => ['ru' => 'Новости агентства', 'en' => 'DspNews agency'],
		];
	}

	/**
	 * @inheritDoc
	 */
	public function getPrimaryKey($asArray = false) {
		return 'ID';
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
		return 'WHNCHNG';
	}
	const ATTR_ID = 'ID';
	const ATTR_CID = 'CID';
	const ATTR_NEWSBANDID = 'NEWSBANDID';
	const ATTR_NEWSDATE = 'NEWSDATE';
	const ATTR_TITLE = 'TITLE';
	const ATTR_MESSAGE = 'MESSAGE';
	const ATTR_VISIBLE = 'VISIBLE';
	const ATTR_HOT = 'HOT';
	const ATTR_WHNCRT = 'WHNCRT';
	const ATTR_WHOCRT = 'WHOCRT';
	const ATTR_WHNCHNG = 'WHNCHNG';
	const ATTR_WHOCHNG = 'WHOCHNG';
	const ATTR_LANGCODE = 'LANGCODE';
	const AVIASALE_NEWS = '0000000001';
	const PASSANGER_NEWS = '0000000002';
	const TOURIST_NEWS = '0000000003';
	const AGENCY_NEWS = '0000000004';
}