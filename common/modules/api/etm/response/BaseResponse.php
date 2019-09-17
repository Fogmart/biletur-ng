<?php

namespace common\modules\api\etm\respone;


/**
 * Базовый класс ответа ETM
 *
 * @package common\modules\api\etm\respone
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class BaseResponse {
	/** @var string */
	public $status;

	/** @var string */
	public $message;

	const STATUS_OK = 'ok';

	const STATUS_ERROR = 'error';
}