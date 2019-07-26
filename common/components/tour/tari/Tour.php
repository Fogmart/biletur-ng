<?php

namespace common\components\tour\tari;

use Sunra\PhpSimple\HtmlDomParser;
use Yii;

/**
 * Класс тура ТариТур
 *
 * @package common\components\tour\tari
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class Tour {
	/** @var int */
	public $offerId;

	/** @var string */
	public $tourName;

	/** @var int */
	public $tourId;

	/** @var string */
	public $tourDate;

	/** @var string */
	public $price;

	/** @var int */
	public $mealId;

	/** @var bool */
	public $ticketsIncluded;

	/** @var string */
	public $tourUrl;

	/** @var string */
	public $spoUrl;

	/** @var string */
	public $resortId;

	/** @var string */
	public $description;

	/** @var string */
	public $image;

	/** @var int */
	public $tourNameId;

	/**
	 * Парсинг недостающей информации с сайта
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function parseInfo() {
		$tourNameId = str_replace('http://www.versiatour.ru/sbornie/tour/bynameid/', '', $this->spoUrl);
		$tourNameId = explode('/', $tourNameId);
		$tourNameId = $tourNameId[0];

		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, $tourNameId]);
		$info = Yii::$app->cache->get($cacheKey);
		if (false === $info) {
			$html = HtmlDomParser::file_get_html($this->spoUrl, false, null, 0);
			$this->description = $html->find('.descr_d1', 0)->text();
			$this->image = str_replace('//', '', $html->find('td .imgrcorners', 0)->getAttribute('src'));
			$this->tourId = $html->find('.print-page', 0)->getAttribute('href');
			$this->tourId = substr($this->tourId, strpos($this->tourId, '?') + 1, 10);
			parse_str($this->tourId, $this->tourId);
			$this->tourId = $this->tourId['tid'];
			$html->clear();
			unset($html);

			print_r($this);
		}
	}
}