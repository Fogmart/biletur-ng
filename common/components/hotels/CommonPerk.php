<?php
namespace common\components\hotels;

use yii\base\Component;

/**
 * Информация о дополнительной услуге: раннему заезду / позднему выезду
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class CommonPerk extends Component {
	/** @var string цена дополнительной услуги (в валюте контракта) */
	public $chargePrice;

	/** @var string цена дополнительной услуги (в валюте поиска) */
	public $showPrice;
}

