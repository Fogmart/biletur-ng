<?php

namespace common\widgets;

use common\base\helpers\Dump;
use common\modules\api\ostrovok\components\objects\BedPlaces;
use common\modules\api\ostrovok\components\objects\CancellationInfo;
use common\modules\api\ostrovok\components\objects\PaymentOptions;
use common\modules\api\ostrovok\components\objects\PaymentType;
use common\modules\api\ostrovok\components\objects\Perk;
use common\modules\api\ostrovok\components\objects\Rate;
use common\modules\api\ostrovok\components\objects\Tax;
use Yii;
use yii\base\Widget;

/**
 * Виджет поиска отелей
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class HotelSearchWidget extends Widget {

	public function run() {

		//Загрузка объектов Rates из API островка
		$response = file_get_contents(Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . 'hotels.json');

		$response = json_decode($response);

		$responseRates = $response->result->hotels[0]->rates;
		$rates = [];
		foreach ($responseRates as $responseRate) {
			$rate = new Rate($responseRate);
			$rate->bed_places = new BedPlaces($responseRate->bed_places);
			$rate->cancellation_info = new CancellationInfo($responseRate->cancellation_info);
			$payment_types = [];
			foreach ($responseRate->payment_options->payment_types as $payment_type) {
				if (property_exists($payment_type, 'perks')) {
					$perks = [];
					foreach ($payment_type->perks as $name => $perk) {
						$perks[$name] = new Perk($perk);
					}
					$payment_type->perks = $perks;
				}
				$payment_types[] = new PaymentType($payment_type);
			}
			$rate->payment_options = new PaymentOptions($responseRate->payment_options);
			$rate->payment_options->payment_types = $payment_types;
			$taxes = [];
			foreach ($responseRate->taxes as $tax) {
				$taxes[] = new Tax($tax);
			}
			$rate->taxes = $taxes;
			$rates[] = $rate;
		}

		Dump::dDie($rates);
	}
}