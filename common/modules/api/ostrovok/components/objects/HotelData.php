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

	//{size} - size of the image, that you can request. List of available values: • 100x100 — crop • 1024x768 — fit • 120x120 — crop • 240x240 — crop • x220 — fit-h • x500 — fit-h crop - image is fit by the width, and is cut equally from the bottom and top till the middle part (of height's value) fit-h - image is fit by the height fit - image
	// is fit into the rectangle of the size in question

	const IMAGE_FORMAT_100X100 = '100x100';
	const IMAGE_FORMAT_120X120 = '120x120';
	const IMAGE_FORMAT_240X240 = '240x240';
	const IMAGE_FORMAT_1024X768 = '1024x768';
	const IMAGE_FORMAT_X220 = 'x220';
	const IMAGE_FORMAT_X500 = 'x500';

}