<?php

namespace common\models\oracle\procedures;

use PDO;
use Yii;

/**
 * @author isakov.v
 *
 * Базовый класс для выполнения процедур.
 * Пока поддержитвывает только один выходной параметр, но если нужно будет несколько, можно расширить.
 * Возможно, при объявлении параметров нужно будет добавить указание типа и размера для всех.
 *
 * Особенность вызова хранимых процедур состоит в том, что обязательно для выходного параметра надо указывать размер.
 * Поэтому этот базовый класс, обеспечивает единообразный вызов процедуры для его наследников.
 * Как это делается - можно посмотреть в наследниках и там где они используются.
 *
 * Изменение сессии NLS_DATE_FORMAT для выполнения процедуры является костылём, призванным обрулить то что кто-то вместо
 * поля DATE делает в таблицах CHAR(10)
 * из-за чего trunc(sysdate) в процедурах при дефолтном для сайта NLS_DATE_FORMAT='DD-MM-YYYY HH24:MI:SS'" возвращает 19 символов,
 * которые в поле CHAR(10) естественно не влезают.
 *
 */
class BaseProcedure {
	/** @var  string */
	public $procedureName;

	/** @var  array */
	public $params;

	public $outParam = ':P_OUT';

	public $outLength = 10;

	public function call() {
		$sql = "alter session set NLS_DATE_FORMAT='DD-MM-YYYY'";
		$connection = Yii::$app->db;
		$command = $connection->createCommand($sql);
		$command->execute();

		$sql = 'CALL ' . $this->procedureName . ' (' . implode(',', array_keys($this->params)) . ')';
		$connection = Yii::$app->db;
		$command = $connection->createCommand($sql);
		foreach ($this->params as $param => &$value) {
			if ($param == $this->outParam) {
				$command->bindParam($param, $value, PDO::PARAM_STR, $this->outLength);
			}
			else {
				$command->bindParam($param, $value, PDO::PARAM_STR);
			}
		}
		$command->execute();
	}

	public function getResult() {
		return $this->params;
	}
}