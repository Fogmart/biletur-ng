<?php

namespace common\components;

use Yii;
use yii\base\Component;
use yii\caching\TagDependency;
use yii\db\Expression;

/**
 * Класс для синхронизации данных с Ораклом ДСП
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class SyncData extends Component {


	/**
	 * @param \common\interfaces\LinkedModels | \yii\db\ActiveRecord | string $modelClass
	 * @param bool                                                                $byDate
	 *
	 * @author  Исаков Владислав <visakov@biletur.ru>
	 */
	public static function execute($modelClass, $byDate = true) {

		$internalField = $modelClass::getInternalInvalidateField();
		$lastChangedDate = $modelClass::find()
			->select(new Expression('MAX("' . $modelClass::getInternalInvalidateField() . '") as ' . $internalField))
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

		$lastChangedDate = date('d-m-Y H:i:s', strtotime($lastChangedDate));

		$diffCount = $modelClass::getLinkedModel()[$modelClass]::find()
			->andWhere(['>', $modelClass::getOuterInvalidateField(), $lastChangedDate])
			->count();

		if ($diffCount === 0) {
			return;
		}

		$diff = $modelClass::getLinkedModel()[$modelClass]::find()
			->andWhere(['>', $modelClass::getOuterInvalidateField(), $lastChangedDate])
			->orderBy($modelClass::getOuterInvalidateField())
			->limit(10)
			->all();

		/** @var \yii\db\ActiveRecord $oneDiff */
		foreach ($diff as $oneDiff) {
			$model = $modelClass::find()
				->andWhere([$modelClass::getOldIdField() => $oneDiff->getPrimaryKey()])
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
			$model->save();
			/*
						try {

						}
						catch (Exception $exception) {
							continue;
						}*/
		}

		TagDependency::invalidate(Yii::$app->cache, [$modelClass]);
	}
}