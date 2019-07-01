<?php

namespace common\base\helpers;

/**
 * Вспомогательный класс для работы с числами
 *
 */
class Number {
	/**
	 * Кодирование числа из одной системы счисления в другую.
	 * Можно задать значение от 10 до 90.
	 *
	 * @author Залатов Александр <zalatov.ao@dns-shop.ru>
	 *
	 * @param string $data     Данные, которые необходимо перевести
	 * @param int    $baseSrcX Разрядность (база) источника
	 * @param int    $baseDstX Разрядность (база) того, что должно получиться
	 *
	 * @return string
	 */
	public static function convertBase($data, $baseSrcX, $baseDstX) {
		if ($baseSrcX === $baseDstX) {
			return $data;
		}

		// -- Если не выходим за рамки доступного, используем стандартную функцию
		if ($baseSrcX <= 36 && $baseDstX <= 36) {
			return base_convert($data, $baseSrcX, $baseDstX);
		}
		// -- -- -- --

		//                  |10   |16                 |36                       |62                   |84   |90
		$encode = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ`~!@#№$;%^&()-_+={}[],<>?"*:';
		$encode10 = substr($encode, 0, 10);
		$encode16 = substr($encode, 0, 16);

		$baseSrc = substr($encode, 0, $baseSrcX);
		$baseDst = substr($encode, 0, $baseDstX);

		$baseSrcA = str_split($baseSrc, 1);
		$baseDstA = str_split($baseDst, 1);
		$dataA = str_split($data, 1);

		$lengthSrc = strlen($baseSrc);
		$lengthDst = strlen($baseDst);
		$lengthData = strlen($data);

		$result = '';

		if ($baseDst === $encode10) {
			$result = 0;

			for ($i = 1; $i <= $lengthData; $i++) {
				$result = bcadd($result, bcmul(array_search($dataA[$i - 1], $baseSrcA), bcpow($lengthSrc, $lengthData - $i)));
			}

			return $result;
		}

		if ($baseSrc !== $encode10) {
			$base10 = static::convertBase($data, $baseSrcX, 10);
		}
		else {
			$base10 = $data;
		}

		if ($base10 < strlen($baseDst)) {
			return $baseDstA[$base10];
		}

		while ('0' !== $base10) {
			$result = $baseDstA[bcmod($base10, $lengthDst)] . $result;
			$base10 = bcdiv($base10, $lengthDst, 0);
		}

		if ($baseDst === $encode16) {
			$result = strtoupper($result);
		}

		return $result;
	}
}