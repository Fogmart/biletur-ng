<?php

namespace common\base\helpers;
/**
 * @author isakov.v
 *
 * Хелпер для работы со строками
 *
 */
class LString {

	public static function rusMonth() {
		return [
			'01' => 'Январь',
			'02' => 'Февраль',
			'03' => 'Март',
			'04' => 'Апрель',
			'05' => 'Май',
			'06' => 'Июнь',
			'07' => 'Июль',
			'08' => 'Август',
			'09' => 'Сентябрь',
			'10' => 'Октябрь',
			'11' => 'Ноябрь',
			'12' => 'Декабрь',
		];
	}

	/**
	 * @param int $i
	 *
	 * @return string
	 */
	public static function humanDateOfWeek($i) {
		$dayArray = self::_weekArray();
		if (array_key_exists($i, $dayArray)) {
			return $dayArray[$i];
		}

		return '';
	}

	private static function _weekArray() {
		return ['1' => 'пн', '2' => 'вт', '3' => 'ср', '4' => 'чт', '5' => 'пт', '6' => 'сб', '0' => 'вс'];
	}

	/**
	 * @param string $str
	 *
	 * @return string
	 */
	public static function convertDepDaysToHuman($str) {
		$str = str_replace('.', '', $str);
		$str = str_split($str);
		$str = array_filter($str);
		$str = array_flip($str);
		$returnStr = [];
		$ruArray = ['1' => 'пн', '2' => 'вт', '3' => 'ср', '4' => 'чт', '5' => 'пт', '6' => 'сб', '7' => 'вс'];
		$enArray = ['1' => 'mon', '2' => 'thu', '3' => 'wed', '4' => 'tue', '5' => 'fri', '6' => 'sat', '7' => 'sun'];
		$allDaysStr = 'каждый день';
		if (\Yii::$app->env->getLanguage() == 'ru') {
			foreach ($str as $key => $s) {
				$returnStr[] = $ruArray[$key];
			}
			$allDaysStr = 'каждый день';
		}
		if (\Yii::$app->env->getLanguage() == 'en') {
			foreach ($str as $key => $s) {
				$returnStr[] = $enArray[$key];
			}
			$allDaysStr = 'every day';
		}
		if (count($returnStr) == 7) {
			return $allDaysStr;
		}

		return implode(',', $returnStr);
	}

	public static function formatMoney($number, $fractional = false, $format = '%.2f') {
		if ($fractional) {
			$number = sprintf($format, $number);
		}
		while (true) {
			$replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1 $2', $number);
			if ($replaced != $number) {
				$number = $replaced;
			}
			else {
				break;
			}
		}

		return $number;
	}
}