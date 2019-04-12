<?php

namespace common\modules\api\ostrovok\components\objects;

use yii\base\Component;

class Hotel extends Component {
	/** @var \common\modules\api\ostrovok\components\objects\HotelData */
	public $data;
	public $debug; //object
	public $error; //object
	/** @var string */
	public $status; //String
}