<?php

namespace common\modules\banner\models;

use common\models\ObjectFile;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;

/**
 * Поля таблицы:
 * @property integer         $id
 * @property string          $title
 * @property string          $url
 * @property string          $beg_date
 * @property string          $end_date
 * @property int             $zone
 * @property string          $utm
 * @property int             $click_count
 * @property int             $show_count
 * @property string          $insert_stamp
 * @property string          $update_stamp
 *
 * @property-read ObjectFile $image
 */
class Banner extends ActiveRecord {

	const ATTR_ID = 'id';
	const ATTR_TITLE = 'title';
	const ATTR_URL = 'url';
	const ATTR_BEG_DATE = 'beg_date';
	const ATTR_END_DATE = 'end_date';
	const ATTR_ZONE = 'zone';
	const ATTR_UTM = 'utm';
	const ATTR_CLICK_COUNT = 'click_count';
	const ATTR_SHOW_COUNT = 'show_count';
	const ATTR_INSERT_STAMP = 'insert_stamp';
	const ATTR_UPDATE_STAMP = 'update_stamp';

	/** @var string */
	public $file;
	const ATTR_FILE = 'file';

	/**
	 * @return array
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function behaviors() {
		return [
			[
				'class'              => TimestampBehavior::class,
				'createdAtAttribute' => 'insert_stamp',
				'updatedAtAttribute' => 'update_stamp',
				'value'              => new Expression('sysdate'),
			],
		];
	}

	/**
	 * @return string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function tableName() {
		return 'banner';
	}

	/**
	 * @return string|string[]
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function primaryKey() {
		return [static::ATTR_ID];
	}

	/**
	 * @return array
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function attributeLabels() {
		return [
			static::ATTR_ID           => 'id',
			static::ATTR_TITLE        => 'Название',
			static::ATTR_URL          => 'URL',
			static::ATTR_UTM          => 'UTM-метки',
			static::ATTR_BEG_DATE     => 'Начало показа',
			static::ATTR_END_DATE     => 'Конец показа',
			static::ATTR_CLICK_COUNT  => 'Переходы',
			static::ATTR_INSERT_STAMP => 'Дата создания',
			static::ATTR_UPDATE_STAMP => 'Дата изменения',
		];
	}

	/**
	 * @return array
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function rules() {
		return [
			[static::ATTR_TITLE, RequiredValidator::class],
		];
	}

	/**
	 *
	 * @return \yii\db\ActiveQuery
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function getImage() {
		return $this->hasOne(ObjectFile::class, [ObjectFile::ATTR_OBJECT_ID => static::ATTR_ID])
			->andWhere([ObjectFile::ATTR_OBJECT => static::class]);
	}

	const REL_IMAGE = 'image';
}
