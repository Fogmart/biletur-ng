<?php

namespace common\modules\pages\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\validators\BooleanValidator;
use yii\validators\RequiredValidator;

/**
 * Поля таблицы:
 * @property integer $id
 * @property string  $title
 * @property string  $seo_title
 * @property string  $seo_description
 * @property string  $seo_keywords
 * @property string  $slug
 * @property string  $html
 * @property integer $is_published
 * @property string  $insert_stamp
 * @property string  $update_stamp
 *
 *
 */
class Page extends ActiveRecord {

	const ATTR_ID = 'id';
	const ATTR_TITLE = 'title';
	const ATTR_SEO_TITLE = 'seo_title';
	const ATTR_SEO_DESCRIPTION = 'seo_description';
	const ATTR_SEO_KEYWORDS = 'seo_keywords';
	const ATTR_SLUG = 'slug';
	const ATTR_HTML = 'html';
	const ATTR_IS_PUBLISHED = 'is_published';
	const ATTR_INSERT_STAMP = 'insert_stamp';
	const ATTR_UPDATE_STAMP = 'update_stamp';

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
		return 'page';
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
			static::ATTR_ID              => 'id',
			static::ATTR_TITLE           => 'Название',
			static::ATTR_SEO_TITLE       => 'SEO Title',
			static::ATTR_SEO_DESCRIPTION => 'SEO Description',
			static::ATTR_SEO_KEYWORDS    => 'SEO Keywords',
			static::ATTR_SLUG            => 'путь URL',
			static::ATTR_HTML            => 'Содержимое',
			static::ATTR_IS_PUBLISHED    => 'Опубликована',
			static::ATTR_INSERT_STAMP    => 'Дата создания',
			static::ATTR_UPDATE_STAMP    => 'Дата изменения',
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
			[static::ATTR_SEO_TITLE, RequiredValidator::class],
			[static::ATTR_SEO_DESCRIPTION, RequiredValidator::class],
			[static::ATTR_SEO_KEYWORDS, RequiredValidator::class],
			[static::ATTR_SLUG, RequiredValidator::class],
			[static::ATTR_HTML, RequiredValidator::class],
			[static::ATTR_IS_PUBLISHED, BooleanValidator::class],
		];
	}

}
