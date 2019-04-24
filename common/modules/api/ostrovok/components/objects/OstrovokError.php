<?php
namespace common\modules\api\ostrovok\components\objects;

use yii\base\Component;

/**
 * Структура ошибки АПИ Островка
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class OstrovokError extends Component {
	/** @var array|null */
	public $extra;
	/** @var string */

	/** @var string */
	public $description;

	/** @var string */
	public $slug;
}