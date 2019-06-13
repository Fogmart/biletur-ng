<?php

namespace common\components\excursion;

use yii\base\Component;

/**
 * Общий класс для приведения скидки цены экскурсий к одному обьекту
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class CommonPriceDiscount extends Component {
	/** @var float */
	public $value;

	/** @var string */
	public $expirationDate;

	/** @var float */
	public $oldPrice;
}