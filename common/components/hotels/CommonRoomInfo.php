<?php
namespace common\components\hotels;

use yii\base\Component;

class CommonRoomInfo extends Component {

	/** @var string Название номера */
	public $title;

	/** @var array Изображения */
	public $images = [];

	/** @var string[] Удобства */
	public $amenities;
}