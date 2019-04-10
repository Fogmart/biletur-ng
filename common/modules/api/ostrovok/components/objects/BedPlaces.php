<?php

namespace common\modules\api\ostrovok\components\objects;

use yii\base\Component;

/**
 * Спальные места
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class BedPlaces extends Component {
	/** @var int */
	public $extra_count;
	/** @var int */
	public $main_count;
	/** @var int */
	public $shared_with_children_count;
	/** @var int */
	public $child_cot_count;
}
