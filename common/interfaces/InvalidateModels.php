<?php

namespace common\interfaces;
/**
 * @author isakov.v
 *
 * Интерфейс необходим для исключения ошибок в процедуре инвалидации кеша.
 * В данном случае он выступает гарантом того, что кеш модели, которая его реализует
 * может быть инвалидирован процедурой инвалидации т.к. она реализует необходимые для этого методы.
 */
interface InvalidateModels {
	public function getInvalidateTime();

	public function getInvalidateField();
}