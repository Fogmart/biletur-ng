<?php

namespace common\components\excursion;

use yii\base\Component;

/**
 * Общий класс для приведения цены экскурсий к одному обьекту
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class CommonPrice extends Component {
	/** @var float */
	public $value;

	/** @var string */
	public $currency;

	/** @var bool Цена от */
	public $priceFrom;

	/** @var string "За человека"/"За экскурсию"  */
	public $unitString;

	/** @var string Полная строка  */
	public $valueString;

	/** @var \common\components\excursion\CommonPriceDiscount */
	public $discount;

}