<?php
namespace common\modules\api\ostrovok\components\objects;

use yii\base\Component;

class BedPlaces extends Component {
	public $extra_count; //int
	public $main_count; //int
	public $shared_with_children_count; //int
	public $child_cot_count; //int
}
