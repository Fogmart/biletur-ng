<?php

namespace common\base\helpers;

use yii\helpers\VarDumper;

/**
 * Class Dump
 * Обёртка над стандартным классом Yii
 */
class Dump extends VarDumper {
	/**
	 * Обёртка вокруг стандартного класса для быстрого дампа
	 *
	 * @param mixed Данные для дампа
	 * @param int   Макс. количество рекурсий (уровней)
	 * @param bool  Должен ли быть подсвечен результат
	 */
	public static function d($var, $depth = 10, $highlight = true) {
		echo self::dumpAsString($var, $depth, $highlight);
	}

	/**
	 * Обёртка вокруг стандартного класса для быстрого дампа с остановкой приложения
	 *
	 * @param mixed Данные для дампа
	 * @param int   Макс. количество рекурсий (уровней)
	 * @param bool  Должен ли быть подсвечен результат
	 */
	public static function dDie($var, $depth = 10, $highlight = true) {
		echo self::dumpAsString($var, $depth, $highlight);
		die;
	}

	/**
	 * Сбор и возврат стека вызовов функций
	 *
	 * @author Zalatov A.
	 *
	 * @return string
	 */
	public static function backtrace() {
		$result = '';

		$rows = debug_backtrace();
		array_shift($rows);

		foreach ($rows as $row) {
			if (isset($row['class']) && $row['class'] != '') {
				$result .= $row['class'] . '::';
			}

			$result .= $row['function'] . '(';

			$first = true;
			foreach ($row['args'] as $i => $arg) {
				if ($first) {
					$first = false;
				}
				else {
					$result .= ', ';
				}

				$s = print_r($arg, true);
				if (strlen($s) > 200) {
					$s = '[long var] of ' . gettype($arg) . ' type';
				}
				$s = '`' . htmlspecialchars($s) . '`';

				$result .= $s;
			}

			$result
				.=
				') at ' . (isset($row['file']) ? $row['file'] : '-') . ':' . (isset($row['line']) ? $row['line'] : '-')
				. '<br />' . "\r\n";
		}

		return $result;
	}

	public static function print_r($subject, $return = false, $depth = 1, $refChain = []) {
		if ($depth > 20) return '* DEPTH GREATER THAN 20 *';

		$result = [];

		if (is_object($subject)) {
			foreach ($refChain as $refVal) {
				if ($refVal === $subject) {
					return '* RECURSION *';
				}
			}

			array_push($refChain, $subject);

			$result[] = get_class($subject) . ' (';

			$subject = (array)$subject;

			// -- Определяем наиболее длинный ключ
			$maxKeyLength = 0;
			foreach (array_keys($subject) as $key) {
				if (mb_strlen($key) <= $maxKeyLength) continue;
				$maxKeyLength = mb_strlen($key);
			}
			// -- -- -- --

			foreach ($subject as $key => $val) {
				$rowLabel = '';

				if ($key[0] == "\0") {
					$keyParts = explode("\0", $key);
					$rowLabel .= $keyParts[2] . (($keyParts[1] == '*') ? ':protected' : ':private');
				} else {
					$rowLabel .= $key;
				}

				$rowValue = self::print_r($val, $return, $depth + 1, $refChain);

				$result[] = str_repeat(' ', $depth * 4) . '\'' . $rowLabel . '\'' . str_repeat(' ', $maxKeyLength - mb_strlen($rowLabel)) . ' => ' . $rowValue;
			}

			$result[] = str_repeat(' ', ($depth - 1) * 4) . ')';
			array_pop($refChain);
		} else if (is_array($subject)) {
			if (count(array_keys($subject)) == 0) {
				$result[] = 'array()';
			} else {
				$result[] = 'array(';

				// -- Определяем наиболее длинный ключ
				$maxKeyLength = 0;
				foreach (array_keys($subject) as $key) {
					if (mb_strlen($key) <= $maxKeyLength) continue;
					$maxKeyLength = mb_strlen($key);
				}
				// -- -- -- --

				foreach ($subject as $key => $val) {
					$result[] = str_repeat(' ', $depth * 4) . '\'' . $key . '\'' . str_repeat(' ', $maxKeyLength - mb_strlen($key)) . ' => ' . self::print_r($val, $return, $depth + 1, $refChain);
				}

				$result[] = str_repeat(' ', ($depth - 1) * 4) . ')';
			}

		} else if (is_bool($subject)) {
			$result[] = ($subject === true ? 'true' : 'false');
		} else if ($subject === null) {
			$result[] = 'null';
		} else if (is_float($subject) || is_int($subject)) {
			$result[] = $subject;
		} else {
			$result[] = '\'' . $subject . '\'';
		}

		$result = implode(PHP_EOL, $result);

		if ($depth == 1 && $return !== true) echo $result;
		return $result;
	}
}