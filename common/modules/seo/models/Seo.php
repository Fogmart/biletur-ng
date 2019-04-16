<?php

namespace common\modules\seo\models;

use common\components\SiteModel;
use Yii;
use yii\validators\DefaultValueValidator;
use yii\validators\RequiredValidator;
use yii\validators\UrlValidator;

/**
 * Поля таблицы:
 * @property integer $id
 * @property string  $url
 * @property string  $seo_title
 * @property string  $seo_description
 * @property string  $seo_keywords
 * @property integer $user_id
 * @property string  $insert_stamp
 * @property string  $update_stamp
 */
class Seo extends SiteModel {

	const ATTR_ID = 'id';
	const ATTR_URL = 'url';
	const ATTR_SEO_TITLE = 'seo_title';
	const ATTR_SEO_DESCRIPTION = 'seo_description';
	const ATTR_SEO_KEYWORDS = 'seo_keywords';
	const ATTR_USER_ID = 'user_id';
	const ATTR_INSERT_STAMP = 'insert_stamp';
	const ATTR_UPDATE_STAMP = 'update_stamp';


	public static function tableName() {
		return 'seo';
	}

	public function attributeLabels() {
		return [
			static::ATTR_ID              => 'id',
			static::ATTR_URL             => 'URL',
			static::ATTR_SEO_TITLE       => 'META TITLE',
			static::ATTR_SEO_DESCRIPTION => 'META DESCRIPTION',
			static::ATTR_SEO_KEYWORDS    => 'META KEYWORDS',
			static::ATTR_USER_ID         => 'user_id',
			static::ATTR_INSERT_STAMP    => 'insert_stamp',
			static::ATTR_UPDATE_STAMP    => 'update_stamp',
		];
	}

	public function rules() {
		return [
			[static::ATTR_URL, RequiredValidator::class],
			[static::ATTR_URL, UrlValidator::class],

			[static::ATTR_SEO_TITLE, RequiredValidator::class],
			[static::ATTR_SEO_DESCRIPTION, RequiredValidator::class],
			[static::ATTR_SEO_KEYWORDS, RequiredValidator::class],
			[static::ATTR_USER_ID, DefaultValueValidator::class, 'value' => Yii::$app->user->id],
		];
	}
}
