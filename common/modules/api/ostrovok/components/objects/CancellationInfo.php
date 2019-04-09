<?php
namespace common\modules\api\ostrovok\components\objects;

use yii\base\Component;

class CancellationInfo extends Component {
	public $policies; //array(Object)
	public $free_cancellation_before; //Date
}