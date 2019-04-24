<?php

namespace common\components\hotels;

use yii\base\Component;

/**
 * Список политик отмены по периодам
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class CommonPolicies extends Component {
	/** @var \common\components\hotels\CommonPenalty */
	public $penalty;

	/** @var null|string дата, с которой активна эта политика (null означает действие на всём периоде до end_at). Время указано в UTC+0 */
	public $startAt;

	/** @var null|string дата, до которой активна эта политика (null означает весь период, начиная со start_at). Время указано в UTC+0 */
	public $endAt;

	/**
	 * если start_at == end_at == null, то у политики отмены нет ограничений по периоду действия (действует всегда)
	 */
}