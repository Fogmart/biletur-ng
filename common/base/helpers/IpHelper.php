<?php

namespace common\base\helpers;

/**
 * Хелпер для работы с IP адресами.
 *
 * Сделан на основе @link https://mebsd.com/coding-snipits/php-ipcalc-coding-subnets-ip-addresses.html
 *
 * @author Медвеженков Владимир <medvezhenkov.v@dns-shop.ru>
 */
class IpHelper {

	/**
	 * Получение маски подсети из конкретного адреса подсети.
	 * Например:
	 * 255.255.255.128 => 255.255.255.0/25
	 * getSubnetMask('255.255.255.128') => 25
	 *
	 * @param string $subnet Подсеть (Например: 255.255.255.128)
	 *
	 * @return integer
	 *
	 * @author Медвеженков Владимир <medvezhenkov.v@dns-shop.ru>
	 */
	public static function getSubnetMask($subnet) {
		$bits = 0;
		$octects = explode('.', $subnet);

		foreach ($octects as $octect) {
			$bits += strlen(str_replace('0', '', decbin($octect)));
		}

		return $bits;
	}

	/**
	 * Входит ли переданный IP адрес в переданную подсеть/маску.
	 * Например:
	 * 10.5.21.30   in 10.5.16.0/20    => isIpMatchSubnet('10.5.21.30',   '10.5.16.0',    20) => true
	 * 192.168.50.2 in 192.168.30.0/23 => isIpMatchSubnet('192.168.50.2', '192.168.30.0', 23) => false
	 *
	 * @param string  $ip         IP адрес
	 * @param string  $subnet     Подсеть (Например: 192.168.30.0)
	 * @param integer $subnetMask Маска подсети (Например: 32)
	 *
	 * @return boolean
	 *
	 * @author Медвеженков Владимир <medvezhenkov.v@dns-shop.ru>
	 */
	public static function isIpMatchSubnet($ip, $subnet, $subnetMask = 32) {
		if ((ip2long($ip) & ~((1 << (32 - $subnetMask)) - 1)) == ip2long($subnet)) {
			return true;
		}

		return false;
	}
}
