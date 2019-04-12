<?php

namespace common\base\helpers;

use DateTime;
use DateTimeZone;
use IntlDateFormatter;

/**
 * Вспомогательный класс для работы с датой и временем.
 *
 * @author Залатов Александр <zalatov.ao@dns-shop.ru>
 */
class DateHelper {
	/** Формат даты:           1970-01-01*/
	const DATE_FORMAT = 'Y-m-d H:i:s';
	const DATE_FORMAT_ORACLE = 'd-m-Y H:i:s';
	/** Формат даты и времени: 1970-01-01 23:59:59 */
	const DATE_TIME_FORMAT = 'Y-m-d H:i:s';
	/** Формат даты и времени: 01.01.1970 23:59:59 */
	const DATE_TIME_FORMAT_RU = 'd.m.Y H:i:s';
	/** Формат даты и времени: 1970-01-01T23:59:59 */
	const DATE_TIME_FORMAT_WEBAPI = 'Y-m-d\TH:i:s';
	/** Формат даты и времени из 1С: 1970-01-01T23:59:59 */
	const DATE_TIME_FORMAT_1C = 'Y-m-d\TH:i:s';

	/** Intl формат даты: 1 января */
	const INTL_FORMAT_DAY_MONTH = 'd MMMM';
	/** Intl формат даты: 1 января 1970 г. */
	const INTL_FORMAT_DATE_RU_EX = 'd MMMM yyyy г.';
	/** Intl формат даты: 01.01.1970 */
	const INTL_FORMAT_DATE_RU = 'dd.MM.yyyy';
	/** Intl формат даты и времени: 1 января 1970 г. 23:59 */
	const INTL_FORMAT_DATE_TIME = 'd MMMM yyyy г. HH:mm';

	/** Количество секунд в одной минуте. */
	const SEC_OF_MINUTE = 60;
	/** Количество секунд в одном часе. */
	const SEC_OF_HOUR = 60 * 60;
	/** Количество секунд в одном дне (24 часа). */
	const SEC_OF_DAY = 24 * 60 * 60;
	/** Количество секунд в одной неделе (7 дней по 24 часа). */
	const SEC_OF_WEEK = 7 * 24 * 60 * 60;
	/** Количество секунд в одном месяце (30 дней по 24 часа). */
	const SEC_OF_MONTH = 30 * 24 * 60 * 60;
	/** Количество секунд в одном году (365 дней по 24 часа). */
	const SEC_OF_YEAR = 365 * 24 * 60 * 60;

	//<editor-fold desc="// [Флаги разниц дат] //">
	const FLAG_DIF_AT_SECONDS = 1;
	const FLAG_DIF_AT_MINUTES = 60;
	const FLAG_DIF_AT_HOURS = 3600;
	const FLAG_DIF_AT_DAYS = 86400;
	//</editor-fold>

	/** Дата начала отсчёта времени: для UNIX. */
	const EPOCH_START_UNIX = '1970-01-01 00:00:00';
	/** Дата начала отсчёта времени: для MSSQL. */
	const EPOCH_START_MSSQL = '1753-01-01 00:00:00';
	/** Дата начала отсчёта времени: для 1С. */
	const EPOCH_START_1C = '0001-01-01 00:00:00';
	/** Дата начала отсчёта времени: нулевое. */
	const EPOCH_START_ZERO = '0000-00-00 00:00:00';

	/** GMT-смещение по городу Владивостоку */
	const GMT_VLADIVOSTOK = 10;

	/** Список месяцев с учётом склонения слова: января, февраля и т.д. */
	const DECLENSION_MONTH_NAMES = [
		1  => 'января',
		2  => 'февраля',
		3  => 'марта',
		4  => 'апреля',
		5  => 'мая',
		6  => 'июня',
		7  => 'июля',
		8  => 'августа',
		9  => 'сентября',
		10 => 'октября',
		11 => 'ноября',
		12 => 'декабря',
	];

	/** Список полных наименований дней недели. */
	const DAYS_OF_WEEK = [
		1 => 'понедельник',
		2 => 'вторник',
		3 => 'среда',
		4 => 'четверг',
		5 => 'пятница',
		6 => 'суббота',
		7 => 'воскресенье',
	];

	/** Список сокращенных наименований дней недели */
	const DAYS_OF_WEEK_SHORT = [
		1 => 'ПН',
		2 => 'ВТ',
		3 => 'СР',
		4 => 'ЧТ',
		5 => 'ПТ',
		6 => 'СБ',
		7 => 'ВС',
	];

	/** @var int Текущее смещение часового пояса. */
	protected static $_currentGmt = 0;

	/** @var DateTimeZone[] Список соотношений смещения по GTM к объекту DateTimeZone (ключ - смещение по GMT, значение - объект DateTimeZone). */
	protected static $_timezones;

	/**
	 * Получение объекта DateTime для указанных даты и времени.
	 *
	 * @param string|int|float      $stamp Дата и время в виде UnixTimestamp или в строковом формате, который доступен для передачи в конструктор DateTime
	 * @param int|DateTimeZone|null $gmt   Смещение часового пояса, явно указанный объект DateTimeZone или NULL, если взять для текущего города
	 *
	 * @return DateTime
	 *
	 * @author Залатов Александр <zalatov.ao@dns-shop.ru>
	 */
	public static function dt($stamp, $gmt = null) {
		// -- Определяем временную зону
		if ($gmt instanceof DateTimeZone) {
			$timezone = clone $gmt;
		}
		else {
			if (null === $gmt) {
				$timezone = static::getCurrentTimezone();
			}
			else {
				$timezone = static::getTimezoneByGmtOffset($gmt);
			}
		}
		// -- -- -- --

		// -- Проверяем, передана ли строка даты или же UnixTimestamp (например, 1481002005.4992)
		if ($stamp instanceof DateTime) {
			$datetime = clone $stamp;
			$datetime->setTimezone($timezone);
		}
		elseif (is_numeric($stamp) || preg_match('/^\d{10}(|\.\d{1,})$/', $stamp)) {
			$datetime = new DateTime('now', $timezone);
			$datetime->setTimestamp($stamp);
		}
		else {
			$datetime = new DateTime($stamp, $timezone);
		}

		// -- -- -- --

		return $datetime;
	}

	/**
	 * Получение объекта DateTime для указанных даты и времени для часового пояса UTC.
	 *
	 * @param string|int|float $stamp Дата и время в виде UnixTimestamp или в строковом формате, который доступен для передачи в конструктор DateTime
	 *
	 * @return DateTime
	 *
	 * @author Залатов Александр <zalatov.ao@dns-shop.ru>
	 */
	public static function utc($stamp) {
		return static::dt($stamp, 0);
	}

	/**
	 * Получение объекта DateTime для указанного часового пояса.
	 *
	 * @param int|DateTimeZone|null $gmt Смещение часового пояса, явно указанный объект DateTimeZone или NULL, если взять для текущего города
	 *
	 * @return DateTime
	 *
	 * @author Залатов Александр <zalatov.ao@dns-shop.ru>
	 */
	public static function now($gmt = null) {
		return static::dt('now', $gmt);
	}

	/**
	 * Получить объект DateTime для текущего времени для часового пояса UTC.
	 *
	 * @return DateTime
	 *
	 * @author Залатов Александр <zalatov.ao@dns-shop.ru>
	 */
	public static function nowUTC() {
		return static::dt('now', 0);
	}

	/**
	 * Получить объект DateTime для текущего времени для часового пояса, под которым запущен PHP.
	 *
	 * @return DateTime
	 *
	 * @author Залатов Александр <zalatov.ao@dns-shop.ru>
	 */
	public static function nowPHP() {
		return new DateTime;
	}

	/**
	 * Форматирование microtime(true) в дату и время в виде текста с указанием миллисекунд.
	 *
	 * Дело в том, что DateTime не поддерживает миллисекунды.
	 *
	 * @param float $microtime Время с дробной частью, полученное через вызов microtime(true)
	 * @param int   $gmt       Смещение часового пояса для итогового результата (по-умолчанию, UTC)
	 *
	 * @return string Дата и время в формате Y-m-d H:i:s.u
	 *
	 * @author Залатов Александр <zalatov.ao@dns-shop.ru>
	 */
	public static function formatMicrotime($microtime, $gmt = 0) {
		$microtime = number_format($microtime, 4, '.', '');// Делаем так, чтобы всегда было 4 знака после точки

		// -- Инициализируем дату с нужным временем с нужным часовым поясом
		$result = static::nowPHP()
			->setTimestamp($microtime)
			->setTimezone(static::getTimezoneByGmtOffset($gmt))
			->format(static::DATE_TIME_FORMAT);
		// -- -- -- --

		$result .= substr($microtime, -5);// Добавляем миллисекунды (5 символов с конца)

		return $result;
	}

	/**
	 * Функция, форматирующая дату по шаблону с учётом локали.
	 *
	 * @param DateTime|int|float|string $stamp   Дата/время, которое необходимо отобразить
	 * @param string                    $pattern Шаблон вывода даты
	 *
	 * @return string
	 *
	 * @author Медвеженков Владимир <medvezhenkov.v@dns-shop.ru>
	 * @author Залатов Александр <zalatov.ao@dns-shop.ru>
	 */
	public static function intlFormat($stamp, $pattern = self::INTL_FORMAT_DATE_TIME) {
		$datetime = static::dt($stamp);

		$formatter = IntlDateFormatter::create('ru_RU', IntlDateFormatter::FULL, IntlDateFormatter::FULL, $datetime->getTimezone());
		$formatter->setPattern($pattern);

		return $formatter->format($datetime);
	}

	/**
	 * Вывод даты в формате с названием города и смещением часового пояса.
	 *
	 * Считается, что входящая метка времени - это дата для часового пояса UTC.
	 * Если это не так, необходимо заранее преобразовать к UTC.
	 *
	 * @param float|int|string|DateTime $stamp     Объект DateTime, метка UnixTimestamp или строка в формате, который будет распознан конструктором DateTime
	 * @param int                       $gmt       Смещение часового пояса
	 * @param string                    $city      Название города
	 * @param bool                      $breakLine Перенести смещение и город на новую строку или нет
	 * @param string                    $format    Формат возвращаемой даты
	 *
	 * @return string
	 *
	 * @author Залатов Александр <zalatov.ao@dns-shop.ru>
	 */
	public static function cityFormat($stamp, $gmt, $city, $breakLine = false, $format = null) {
		$datetime = static::utc2gmt($stamp, $gmt);

		$format = (null === $format ? 'd-m-Y H:i' : $format);

		$result = [
			$datetime->format($format),
			(true === $breakLine ? '<br>' : ' '),
			'(',
			'GMT' . ($datetime->getOffset() >= 0 ? '+' : '-'),
			substr(static::intSecondsToString(abs($datetime->getOffset())), 0, 5),
			('' === $city ? '' : ' ' . $city),
			')',
		];

		return implode('', $result);
	}

	/**
	 * Функция, возвращающая стамп даты/времени в секундах без учёта часов, минут и секунд (они просто обнуляются).
	 *
	 * @param DateTime|int|null $dateTime Дата и время, для которых нужно вернуть стамп (если null, то взять для текущего дня)
	 *
	 * @return int
	 */
	public static function getDateStampNum($dateTime = null) {
		if (is_int($dateTime) || is_float($dateTime) || is_double($dateTime)) {
			return (int)floor($dateTime);
		}
		$stamp = ($dateTime instanceof DateTime ? $dateTime->getTimestamp() : time());

		// Округляем стамп даты/времени до суток, т.е. Y-m-d H:i:s в Y-m-d 00:00:00
		return (int)(floor($stamp / static::SEC_OF_DAY) * static::SEC_OF_DAY);
	}

	/**
	 * Функция, возвращающая разницу между днями в указанной величине:
	 *    FLAG_DIF_AT_SECONDS        разница в секундах
	 *    FLAG_DIF_AT_MINUTES        разница в минутах
	 *    FLAG_DIF_AT_HOURS        разница в часах
	 *    FLAG_DIF_AT_DAYS        разница в днях
	 *
	 * @param DateTime|int $dateA
	 * @param DateTime|int $dateB
	 * @param int          $flag          Предустановленный флаг времени. Также можно передать любое число, к примеру: если передать 2592000 (30 дней), то можно
	 *                                    получить приблизительную разницу в месяцах
	 *
	 * @return int
	 */
	public static function getDatesDif($dateA, $dateB, $flag = self::FLAG_DIF_AT_DAYS) {
		$datesDif = self::getDateStampNum($dateA) - self::getDateStampNum($dateB);

		return (int)floor($datesDif / $flag);
	}

	/**
	 * Проверка, приходится ли дата на текущий день с учётом локали.
	 *
	 * @param DateTime $datetime Проверяемая дата
	 * @param int      $gmt      Смещение часового пояса
	 *
	 * @return bool
	 *
	 * @author Казанцев Александр <kazancev.al@dns-shop.ru>
	 * @author Залатов Александр <zalatov.ao@dns-shop.ru>
	 */
	public static function isToday(DateTime $datetime, $gmt) {
		// -- Клонируем входящую дату, чтобы не менять переданный объект, и устанавливаем ей локальную зону
		$dt = clone $datetime;
		$dt->setTimezone(static::getTimezoneByGmtOffset($gmt));

		// -- -- -- --

		return ($dt->format('Ymd') === static::now($gmt)->format('Ymd'));
	}

	/**
	 * Возвращает строковое значение даты относительно текущей даты: [позавчера, вчера, сегодня, завтра, послезавтра] или дату.
	 *
	 * @param DateTime $datetime Объект даты
	 *
	 * @return string
	 *
	 * @author Медвеженков Владимир <medvezhenkov.v@dns-shop.ru>
	 * @author Залатов Александр <zalatov.ao@dns-shop.ru>
	 */
	public static function getDateTitleRelToday($datetime) {
		// -- Инициализируем даты
		$argDateTime = clone $datetime;// Чтобы не изменять входной объект
		$argDateTime->setTimezone(static::getCurrentTimezone());

		$nowDateTime = static::now();

		$argDateTime->setTime(0, 0, 0);
		$nowDateTime->setTime(0, 0, 0);
		// -- -- -- --

		$diff = (int)floor(($argDateTime->getTimestamp() - $nowDateTime->getTimestamp()) / static::SEC_OF_DAY);

		// -- Добавляем год, если год отличается от текущего
		if ($argDateTime->format('Y') === $nowDateTime->format('Y')) {
			$datePattern = static::INTL_FORMAT_DAY_MONTH;
		}
		else {
			$datePattern = static::INTL_FORMAT_DATE_RU_EX;
		}
		// -- -- -- --

		// -- Если попадает в нужный диапазон, выводим понятное название
		$names = [
			-2 => 'Позавчера',
			-1 => 'Вчера',
			0  => 'Сегодня',
			1  => 'Завтра',
			2  => 'Послезавтра',
		];
		if (array_key_exists($diff, $names)) {
			return $names[$diff];
		}

		// -- -- -- --

		return static::intlFormat($argDateTime, $datePattern);
	}

	/**
	 * Преобразование даты из UTC в дату для указанного часового пояса.
	 *
	 * @param float|int|string|DateTime $date Объект DateTime, метка UnixTimestamp или строка в формате, который будет распознан конструктором DateTime
	 * @param int                       $gmt  Смещение часового пояса
	 *
	 * @return DateTime
	 *
	 * @author Залатов Александр <zalatov.ao@dns-shop.ru>
	 */
	public static function utc2gmt($date, $gmt) {
		if ($date instanceof DateTime) {
			$dt = clone $date;
		}
		else {
			$dt = static::utc($date);
		}

		$dt->setTimezone(static::getTimezoneByGmtOffset($gmt));

		return $dt;
	}

	/**
	 * Преобразование даты из UTC в дату Владивостока.
	 *
	 * @param string|DateTime $date Объект DateTime или дата и время строкой в формате, который будет распознан конструктором DateTime
	 *
	 * @return DateTime
	 *
	 * @author Залатов Александр <zalatov.ao@dns-shop.ru>
	 */
	public static function utc2vlk($date) {
		return static::utc2gmt($date, static::GMT_VLADIVOSTOK);
	}

	/**
	 * Преобразование даты из указанного часового пояса в дату по UTC.
	 *
	 * @param string $date Дата и время в формате, который будет успешно обработан конструктором DateTime
	 * @param int    $gmt  Смещение часового пояса
	 *
	 * @return DateTime
	 *
	 * @author Залатов Александр <zalatov.ao@dns-shop.ru>
	 */
	public static function gmt2utc($date, $gmt) {
		$dateTime = new DateTime($date, static::getTimezoneByGmtOffset($gmt));

		$dateTime->setTimezone(new DateTimeZone('UTC'));

		return $dateTime;
	}

	/**
	 * Установить текущее смещение часового пояса.
	 *
	 * @param int $gmt Смещение часового пояса
	 *
	 * @author Залатов Александр <zalatov.ao@dns-shop.ru>
	 */
	public static function setCurrentGmt($gmt) {
		static::$_currentGmt = $gmt;
	}

	/**
	 * Получение объекта временной зоны DateTimeZone по текущему городу на сайт.
	 *
	 * @return DateTimeZone
	 *
	 * @author Медвеженков Владимир <medvezhenkov.v@dns-shop.ru>
	 */
	public static function getCurrentTimezone() {
		return static::getTimezoneByGmtOffset(static::$_currentGmt);
	}

	/**
	 * Получение объекта временной зоны DateTimeZone по смещению GMT.
	 *
	 * @param int $gmt Смещение часового пояса
	 *
	 * @return DateTimeZone
	 *
	 * @author Залатов Александр <zalatov.ao@dns-shop.ru>
	 */
	public static function getTimezoneByGmtOffset($gmt) {
		// -- Если указан ноль, то явно указываем, что это зона UTC (в частности, библиотека Intl выдаёт ошибку, если таймзона указана как GMT+00:00)
		if (0 === $gmt) {
			return new DateTimeZone('UTC');
		}
		// -- -- -- --

		// -- Если зоны ещё не были инициализированы, инициализируем их, чтобы каждый раз не пробегаться по массиву
		if (null === static::$_timezones) {
			$dtUTC = new DateTime('now', new DateTimeZone('UTC'));// Для вычисления смещения нужна дата - берём UTC
			$list = DateTimeZone::listIdentifiers(DateTimeZone::ALL);// Получаем список всех доступных временных зон

			static::$_timezones = [];
			foreach ($list as $z) {
				$timezone = new DateTimeZone($z);
				$offset = $timezone->getOffset($dtUTC);

				// -- Нам нужны смещения без минут (ведь могут быть смещения типа GMT+03:30)
				if (0 === $offset % static::SEC_OF_HOUR) {
					$offset = $offset / static::SEC_OF_HOUR;// Вычисляем количество часов в смещении

					// -- Если такой таймзоны ещё нет в списке, добавляем
					if (false === array_key_exists($offset, static::$_timezones)) {
						$formatter = IntlDateFormatter::create('ru_RU', IntlDateFormatter::FULL, IntlDateFormatter::FULL, $timezone);
						if (null !== $formatter) {
							static::$_timezones[$offset] = $timezone;
						}
					}
					// -- -- -- --
				}
			}

			ksort(static::$_timezones);
		}

		// -- -- -- --

		return static::$_timezones[$gmt];
	}

	/**
	 * Возвращает даты начала и конца недели
	 *
	 * @author kanatnikov a.
	 * @return string[] ['start' => дата начала недели, 'end' => дата конца недели]
	 */
	public static function getCurrentWeekRange() {
		$currentTime = time();

		return [
			'start' => date('N', $currentTime) === '1' ? date('Y-m-d', $currentTime) : date('Y-m-d', strtotime('last monday', $currentTime)),
			'end'   => date('N', $currentTime) === '7' ? date('Y-m-d', $currentTime) : date('Y-m-d', strtotime('next sunday', $currentTime))
		];
	}

	/**
	 * Получение полного наименования дня недели.
	 *
	 * @param DateTime $dateTime Дата и время, по которому необходимо вернуть день недели
	 *
	 * @return string
	 *
	 * @author Залатов Александр <zalatov.ao@dns-shop.ru>
	 */
	public static function getDayOfWeekTitle($dateTime) {
		$number = $dateTime->format('N');

		return static::DAYS_OF_WEEK[$number];
	}

	/**
	 * Форматирование секунд из числа в текст.
	 *
	 * Например, из 86400 получается 24:00:00.
	 *
	 * @param int|float $seconds     Количество секунд (можно с миллисекундами)
	 * @param bool      $canNegative Может ли иметь отрицательное значение
	 *
	 * @return string
	 *
	 * @author Залатов Александр <zalatov.ao@dns-shop.ru>
	 */
	public static function intSecondsToString($seconds, $canNegative = true) {
		if (false === $canNegative && $seconds < 0) {
			$seconds = 0;
		}

		$hours = floor($seconds / 60 / 60);
		$minutes = floor(($seconds - $hours * 60 * 60) / 60);
		$seconds = floor($seconds - $minutes * 60 - $hours * 60 * 60);

		$hours = str_pad($hours, 2, '0', STR_PAD_LEFT);
		$minutes = str_pad($minutes, 2, '0', STR_PAD_LEFT);
		$seconds = str_pad($seconds, 2, '0', STR_PAD_LEFT);

		return $hours . ':' . $minutes . ':' . $seconds;
	}


	/**
	 * Получение текстового представления временного периода.
	 * (Например, "с 5 по 15 августа 2016 года", "с 5 августа по 20 ноября 2016 года", "c 10 декабря 2016 года по 10 января 2017 года)
	 *
	 * @param DateTime $startDate Начало периода
	 * @param DateTime $endDate   Конец периода
	 *
	 * @return string
	 *
	 * @author Дегтярев Илья <degtyarev.iv@dns-shop.ru>
	 */
	public static function getDurationText($startDate, $endDate) {
		$result = ['с'];
		$monthNames = static::DECLENSION_MONTH_NAMES;

		if ($startDate->format('Y') !== $endDate->format('Y')) { // Если года начала и конца периода разные
			$result[] = $startDate->format('j');
			$result[] = $monthNames[$startDate->format('n')];
			$result[] = $startDate->format('Y');
			$result[] = 'года';
		}
		elseif ($startDate->format('n') !== $endDate->format('n')) { // Если месяца начала и конца периода разные
			$result[] = $startDate->format('j');
			$result[] = $monthNames[$startDate->format('n')];
		}
		else {
			$result[] = $startDate->format('j');
		}

		$result[] = 'по';
		$result[] = $endDate->format('j');
		$result[] = $monthNames[$endDate->format('n')];
		$result[] = $endDate->format('Y');
		$result[] = 'года';

		return implode(' ', $result);
	}
}