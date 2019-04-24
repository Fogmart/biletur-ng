<?php
namespace common\modules\api\ostrovok\components\objects;

use yii\base\Component;

/**
 * Базовая структураответа АПИ Островка
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class OstrovokResponse extends Component {
	/** @var string */
	public $debug;
	/** @var array */
	public $result;
	/** @var \common\modules\api\ostrovok\components\objects\OstrovokError */
	public $error;

	/** @var array */
	public $data;
}