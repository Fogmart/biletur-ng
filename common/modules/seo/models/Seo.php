<?php

namespace common\modules\seo\models;

use common\components\SiteModel;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\caching\TagDependency;
use yii\db\Expression;
use yii\validators\DefaultValueValidator;
use yii\validators\NumberValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;
use yii\validators\UrlValidator;

/**
 * Поля таблицы:
 * @property integer $id
 * @property string  $url
 * @property string  $seo_title
 * @property string  $seo_description
 * @property string  $seo_keywords
 * @property integer $user_id
 * @property string  $object
 * @property int     $object_id
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
	const ATTR_OBJECT = 'object';
	const ATTR_OBJECT_ID = 'object_id';
	const ATTR_INSERT_STAMP = 'insert_stamp';
	const ATTR_UPDATE_STAMP = 'update_stamp';

	const SCENARIO_URL = 'sc_url';
	const SCENARIO_OBJECT = 'sc_object';

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
	 * @inheritDoc
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */

	public static function tableName() {
		return 'seo';
	}

	/**
	 * @inheritDoc
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function attributeLabels() {
		return [
			static::ATTR_ID              => 'id',
			static::ATTR_URL             => 'URL',
			static::ATTR_SEO_TITLE       => 'META TITLE',
			static::ATTR_SEO_DESCRIPTION => 'META DESCRIPTION',
			static::ATTR_SEO_KEYWORDS    => 'META KEYWORDS',
			static::ATTR_OBJECT          => 'Обьект',
			static::ATTR_OBJECT_ID       => 'ID Обьекта',
			static::ATTR_USER_ID         => 'user_id',
			static::ATTR_INSERT_STAMP    => 'insert_stamp',
			static::ATTR_UPDATE_STAMP    => 'update_stamp',
		];
	}

	/**
	 * @inheritDoc
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function rules() {
		return [
			[static::ATTR_URL, RequiredValidator::class, 'on' => self::SCENARIO_URL],
			[static::ATTR_URL, UrlValidator::class],
			[static::ATTR_SEO_TITLE, RequiredValidator::class],
			[static::ATTR_SEO_DESCRIPTION, RequiredValidator::class],
			[static::ATTR_SEO_KEYWORDS, RequiredValidator::class],
			[static::ATTR_OBJECT, RequiredValidator::class, 'on' => self::SCENARIO_OBJECT],
			[static::ATTR_OBJECT_ID, RequiredValidator::class, 'on' => self::SCENARIO_OBJECT],
			[static::ATTR_OBJECT, StringValidator::class],
			[static::ATTR_OBJECT_ID, NumberValidator::class],
			[static::ATTR_USER_ID, DefaultValueValidator::class, 'value' => Yii::$app->user->id],
		];
	}

	/**
	 * @return array
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function scenarios() {
		$scenarios = parent::scenarios();
		$scenarios[self::SCENARIO_URL] = [static::ATTR_URL];
		$scenarios[self::SCENARIO_OBJECT] = [static::ATTR_OBJECT, static::ATTR_OBJECT_ID];

		return $scenarios;
	}

	/**
	 * Метаданные по URL
	 *
	 * @param string $url
	 * @param \yii\web\View
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function registerMeta($url, $view) {
		/** @var static $meta */
		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, $url]);
		$meta = Yii::$app->cache->get($cacheKey);
		if (false === $meta) {
			$meta = static::find()->one([static::ATTR_URL => $url])->one();
			Yii::$app->cache->set($cacheKey, $meta, null, new TagDependency(['tags' => static::class]));
		}

		static::renderMeta($meta, $view);
	}

	/**
	 * Метаданные для обьекта
	 *
	 * @param string $object
	 * @param int    $objectId
	 * @param \yii\web\View
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function registerMetaByObject($object, $objectId, $view) {
		/** @var static $meta */
		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, $object, $objectId]);
		$meta = Yii::$app->cache->get($cacheKey);
		if (false === $meta) {
			$meta = static::find()->one([static::ATTR_OBJECT => $object, static::ATTR_OBJECT_ID => $objectId])->one();
			Yii::$app->cache->set($cacheKey, $meta, null, new TagDependency(['tags' => static::class]));
		}

		static::renderMeta($meta, $view);
	}

	/**
	 * Регистрация тегов
	 *
	 * @param static        $meta
	 * @param \yii\web\View $view
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public static function renderMeta($meta, $view) {
		if (null === $meta) {
			return;
		}

		$view->title = $meta->seo_title;

		$view->registerMetaTag([
			'name'    => 'title',
			'content' => $meta->seo_title
		]);

		$view->registerMetaTag([
			'name'    => 'description',
			'content' => $meta->seo_description
		]);

		$view->registerMetaTag([
			'name'    => 'keywords',
			'content' => $meta->seo_keywords
		]);

		/**@todo дальше регаем опенграфы и еще что-то */
	}
}
