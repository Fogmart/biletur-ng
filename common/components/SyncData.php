<?php

namespace common\components;

use common\base\helpers\DateHelper;
use Yii;
use yii\base\Component;
use yii\caching\TagDependency;
use yii\db\Expression;

/**
 * Класс для синхронизации данных на сайте с Ораклом ДСП
 *
 * @see \common\interfaces\ILinkedModels
 *
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class SyncData extends Component {

	/**
	 * @param \common\interfaces\ILinkedModels | \yii\db\ActiveRecord | string $modelClass
	 * @param bool                                                             $byDate
	 *
	 * @author  Исаков Владислав <visakov@biletur.ru>
	 */
	public static function execute($modelClass, $byDate = true) {

		$internalField = $modelClass::getInternalInvalidateField();
		$lastChangedDate = $modelClass::find()
			->select(new Expression('MAX("' . $modelClass::getInternalInvalidateField() . '") as "' . $internalField . '"'))
			->one();

		$lastChangedDate = $lastChangedDate->$internalField;
		if (empty($lastChangedDate)) {
			if ($byDate) {
				$lastChangedDate = '01-01-1970 00:00:00';
			}
			else {
				$lastChangedDate = 0;
			}
		}

		$lastChangedDate = date('Y-m-d H:i:s', strtotime($lastChangedDate));

		echo date(DateHelper::DATE_FORMAT_ORACLE) . ': ' . 'Select diff to ' . $modelClass . PHP_EOL;

		$diffCount = $modelClass::getLinkedModel()[$modelClass]::find()
			->andWhere(['>', $modelClass::getOuterInvalidateField(), $lastChangedDate])
			->count();

		if ($diffCount == 0) {
			echo date(DateHelper::DATE_FORMAT_ORACLE) . ': ' . 'No diff to ' . $modelClass . PHP_EOL;

			return;
		}

		$diff = $modelClass::getLinkedModel()[$modelClass]::find()
			->andWhere(['>', $modelClass::getOuterInvalidateField(), $lastChangedDate])
			->orderBy($modelClass::getOuterInvalidateField())
			->limit(5000)
			->all();

		$i = 1;

		/** @var \yii\db\ActiveRecord $oneDiff */
		foreach ($diff as $oneDiff) {
			$id = $oneDiff->pk;
			static::showStatus($i, count($diff));
			$model = $modelClass::find()
				->andWhere([$modelClass::getOldIdField() => $oneDiff->$id])
				->one();

			if (null === $model) {
				$model = new $modelClass;
			}

			foreach ($oneDiff->attributes as $attribute => $val) {
				if (!array_key_exists($attribute, $modelClass::getLinkedFields())) {
					continue;
				}

				$filteredVal = $modelClass::getConvertedField($attribute, $val);
				$field = $modelClass::getLinkedFields()[$attribute];
				$model->$field = $filteredVal;
			}

			try {
				$model->save();
			}
			catch (\Exception $exception) {
				echo $exception->getMessage() . PHP_EOL;
				continue;
			}

			$i++;
		}

		echo date(DateHelper::DATE_FORMAT_ORACLE) . ': ' . $modelClass . ' - синхронизировано' . PHP_EOL;
		usleep(90000);
		TagDependency::invalidate(Yii::$app->cache, [$modelClass]);
	}

	/**
	 * show a status bar in the console
	 *
	 * <code>
	 * for($x=1;$x<=100;$x++){
	 *
	 *     show_status($x, 100);
	 *
	 *     usleep(100000);
	 *
	 * }
	 * </code>
	 *
	 * @param int $done  how many items are completed
	 * @param int $total how many items are to be done total
	 * @param int $size  optional size of the status bar
	 *
	 * @return  void
	 *
	 */
	public static function showStatus($done, $total, $size = 30) {

		static $start_time;

		// if we go over our bound, just ignore it
		if ($done > $total)
			return;

		if (empty($start_time))
			$start_time = time();
		$now = time();

		$perc = (double)($done / $total);

		$bar = floor($perc * $size);

		$status_bar = "\r[";
		$status_bar .= str_repeat("=", $bar);
		if ($bar < $size) {
			$status_bar .= ">";
			$status_bar .= str_repeat(" ", $size - $bar);
		}
		else {
			$status_bar .= "=";
		}

		$disp = number_format($perc * 100, 0);

		$status_bar .= "] $disp%  $done/$total";

		$rate = ($now - $start_time) / $done;
		$left = $total - $done;
		$eta = round($rate * $left, 2);

		$elapsed = $now - $start_time;

		$status_bar .= " remaining: " . number_format($eta) . " sec.  elapsed: " . number_format($elapsed) . " sec.";

		echo "$status_bar  ";

		flush();

		// when done, send a newline
		if ($done == $total) {
			echo "\n";
		}

	}

}