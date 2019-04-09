<?php
namespace common\modules\api\ostrovok\components\queries;

use yii\base\Component;

/**
 * Class RegionHotel
 *
 * @package common\modules\api\ostrovok\components\queries
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class RegionHotel extends Component {
	/** @var string */
	public $query;
	const ATTR_QUERY = 'query';

	/** @var string */
	public $format = 'json';
	const ATTR_FORMAT = 'format';

	/** @var string */
	public $lang = 'ru';
	const ATTR_LANG = 'lang';
}