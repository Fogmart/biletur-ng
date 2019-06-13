<?php

namespace common\components\excursion;

use yii\base\Component;

/**
 * Общий класс для приведения города экскурсий к одному обьекту
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class CommonCountry extends Component {
	/** @var int */
	public $id;
	/** @var string */
	public $nameRu;
	/** @var string */
	public $nameEn;
	/** @var string */
	public $currency;
	/** @var string */
	public $inObjPhrase;
	/** @var int */
	public $experienceCount;
	/** @var string */
	public $url;
}