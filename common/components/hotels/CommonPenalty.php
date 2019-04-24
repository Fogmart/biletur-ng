<?php
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 */

namespace common\components\hotels;

use yii\base\Component;

/**
 * Инцормация о штрафе
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class CommonPenalty extends Component {
	/** @var string размер штрафа */
	public $amount;

	/** @var null|float процент от стоимости бронирования; может иметь значение null */
	public $percent;

	/** @var string валюта штрафа */
	public $currencyCode;

	/** @var string курс валюты штрафа в рублях */
	public $currencyRateToRub;
}