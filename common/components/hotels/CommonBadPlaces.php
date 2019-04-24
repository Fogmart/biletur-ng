<?php

namespace common\components\hotels;

use yii\base\Component;

class CommonBadPlaces extends Component {
	/** @var int Кол-во детских кроваток*/
	public $childCotCount;
	/** @var int */
	public $extraCount;
	/** @var int */
	public $mainCount;
	/** @var int */
	public $sharedWithChildrenCount;
}