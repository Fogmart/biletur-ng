<?php

namespace common\modules\api\ostrovok\components\objects;

use yii\base\Component;

class RoomGroup extends Component {
	/** @var string[] */
	public $images;
	/** @var string */
	public $name;
	/** @var string[] */
	public $room_amenities;
	/** @var int */
	public $room_group_id;
}