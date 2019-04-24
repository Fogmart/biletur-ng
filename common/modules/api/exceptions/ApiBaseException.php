<?php

namespace common\modules\api\exceptions;

use Yii;
use yii\base\Exception;

class ApiBaseException extends Exception {
	public function __construct($message, $code = null, $previous = null) {
		parent::__construct($message, $code, $previous);
		Yii::error(
			$message . $this->getTraceAsString(),
			get_called_class()
		);
	}
}
