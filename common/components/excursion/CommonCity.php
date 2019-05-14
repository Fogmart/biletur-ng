<?php

namespace common\components\excursion;

use yii\base\Component;

/**
 * Общий класс для приведения города экскурсий к одному обьекту
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class CommonCity extends Component {

	/** @var int */
	public $id;

	/** @var string */
	public $nameRu;

	/** @var string */
	public $nameEn;

	/** @var string */
	public $iataCode;

	/** @var int */
	public $experienceCount;

	/** @var string */
	public $inObjPhrase;

	/** @var string */
	public $url;

	/** @var string */
	public $image;

	/** @var float */
	public $utcOffset;

	/** @var \common\modules\api\tripster\components\objects\Country */
	public $country;
}