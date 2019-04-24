<?php
namespace common\modules\api\ostrovok\components\objects;

use yii\base\Component;

/**
 * Информация об отеле
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class Rate extends Component {
	public $non_refundable; //boolean
	public $book_hash; //object
	public $serp_filters; //array(Object)
	public $b2b_recommended_price; //object
	public $hotelpage; //String
	public $room_amenities; //array(Object)
	public $room_size; //String
	public $room_name; //String
	public $available_rooms; //int
	public $rate_currency; //String
	public $room_group_id; //int
	public $smoking_policies; //object
	public $value_adds; //array(Object)

	public $images; //array(Object)
	/** @var \common\modules\api\ostrovok\components\objects\BedPlaces */
	public $bed_places;
	/** @var string[] */
	public $daily_prices;

	public $bed_types; //array(Object)
	/** @var string */
	public $room_description; //String
	/** @var \common\modules\api\ostrovok\components\objects\CancellationInfo */
	public $cancellation_info;
	/** @var string */
	public $room_type_id;
	/** @var string */
	public $rate_price;
	/** @var \common\modules\api\ostrovok\components\objects\PaymentOptions */
	public $payment_options;
	/** @var \common\modules\api\ostrovok\components\objects\Tax[] */
	public $taxes;
	/** @var string */
	public $availability_hash;

	/** @var string */
	public $meal;
}