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

	/** @var int */
	public $hotelId;

	/**
	 * Парсинг недостающей информации с сайта
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function parseInfo() {
		$tourNameId = str_replace('http://www.versiatour.ru/sbornie/tour/bynameid/', '', $this->spoUrl);
		$tourNameId = explode('/', $tourNameId);
		$tourNameId = $tourNameId[0];

		$cacheKey = Yii::$app->cache->buildKey([__METHOD__, $tourNameId, 1]);
		$info = Yii::$app->cache->get($cacheKey);
		if (false === $info) {
			$html = HtmlDomParser::file_get_html($this->spoUrl, false, null, 0);
			$description = $html->find('.descr_d1', 0)->text();
			$image = $html->find('td .imgrcorners', 0);

			if (null !== $image) {
				$image = str_replace('//', '', $this->image->getAttribute('src'));
			}

			$tourId = $html->find('.print-page', 0)->getAttribute('href');
			$tourId = substr($tourId, strpos($tourId, '?') + 1, 10);
			parse_str($tourId, $tourId);
			$tourId = $tourId['tid'];
			$html->clear();
			unset($html);

			$info = [
				'description' => $description,
				'image'       => $image,
				'tourId'      => $tourId
			];

			Yii::$app->cache->set($cacheKey, $info, null);
		}

		$this->tourNameId = $tourNameId;
		$this->description = $info['description'];
		$this->image = $info['image'];
		$this->tourId = $info['tourId'];
	}
}