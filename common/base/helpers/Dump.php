<?php

namespace common\components\helpers;

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
}