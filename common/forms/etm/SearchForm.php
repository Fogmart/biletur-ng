<?php

namespace common\forms\etm;

use yii\base\Model;

/**
 * Форма поиска авиабилетов
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class SearchForm extends Model {

	public $airportFrom;
	const ATTR_AIRPORT_FROM = 'airportFrom';

	public $airportTo;
	const ATTR_AIRPORT_TO = 'airportTo';

}