<?php

namespace common\modules\api\ostrovok\components\objects;

class HotelData {
	/** @var string */
	public $address;
	/** @var \common\modules\api\ostrovok\components\objects\AmenityGroup[] */
	public $amenity_groups;
	/** @var string */
	public $check_in_time;
	/** @var string */
	public $check_out_time;
	/** @var \common\modules\api\ostrovok\components\objects\DescriptionStruct[] */
	public $description_struct;
	/** @var string */
	public $email;
	/** @var string */
	public $id;
	/** @var string[] */
	public $images;
	/** @var string */
	public $kind;
	/** @var float */
	public $latitude;
	/** @var float */
	public $longitude;
	/** @var string */
	public $name;
	/** @var string */
	public $phone;
	/** @var \common\modules\api\ostrovok\components\objects\PolicyStruct[] */
	public $policy_struct;
	/** @var string */
	public $postal_code;
	/** @var \common\modules\api\ostrovok\components\objects\Region */
	public $region;
	/** @var \common\modules\api\ostrovok\components\objects\RoomGroup[] */
	public $room_groups;
	/** @var int */
	public $semantic_version;
	/** @var int */
	public $star_rating;
}