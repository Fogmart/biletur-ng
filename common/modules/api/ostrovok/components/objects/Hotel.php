<?php
namespace common\modules\api\ostrovok\components\objects;

/**
 * Информация об отеле
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class Hotel {
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
	public $bed_places; //BedPlaces
	public $daily_prices; //array(String)

	public $bed_types; //array(Object)
	public $room_description; //String

	/** @var \common\modules\api\ostrovok\components\objects\CancellationInfo */
	public $cancellation_info;
	public $room_type_id; //String
	public $rate_price; //String

	/** @var \common\modules\api\ostrovok\components\objects\PaymentOptions */
	public $payment_options;
	public $taxes; //array(Object)
	public $availability_hash; //String
}