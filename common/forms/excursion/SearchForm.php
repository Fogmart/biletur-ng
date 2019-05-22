<?php

namespace common\forms\excursion;

use sem\helpers\ArrayHelper;
use Yii;
use yii\base\Model;
use yii\validators\RequiredValidator;
use common\components\excursion\CommonExcursion;

/**
 * Общая форма поиска экскурсий
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class SearchForm extends Model {

	/** @var string */
	public $title;
	const ATTR_TITLE = 'title';

	/** @var string */
	public $source = self::API_SOURCE_TRIPSTER;
	const ATTR_SOURCE = 'source';

	/** @var CommonExcursion[] Экскурсии */
	public $result = [];

	const API_SOURCE_TRIPSTER = 0;

	/**
	 * @return array
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function rules() {
		return [
			[static::ATTR_TITLE, RequiredValidator::class, 'message' => 'Пожалуйста, выберите название или город'],
		];
	}

	public function search() {
		$this->result = $this->searchTripster();

		return $this->result;
	}

	/**
	 * Поиск экскурсий по Апи Трипстер
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function searchTripster() {


		return [];
	}

	/**
	 * Возвращает данные последнего автокомплита для их восстановления после отправки формы
	 *
	 * @return mixed
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function getLastAutocompleteTripster() {
		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, Yii::$app->session->id]);
		$result = Yii::$app->cache->get($cacheKey);

		if (false === $result) {
			$result = [];
		}
		else {
			$result = ArrayHelper::map($result['results'], 'id', 'text');
		}

		return $result;
	}

}