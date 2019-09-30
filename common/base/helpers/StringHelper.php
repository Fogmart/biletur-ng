<?php

namespace common\base\helpers;

use common\base\validators\PhoneValidator;
use Transliterator;
use yii\helpers\Html;
use yii\validators\EmailValidator;

/**
 * @inheritdoc
 *
 * @author Залатов Александр <zalatov.ao@dns-shop.ru>
 */
class StringHelper extends \yii\helpers\StringHelper {
	/** @var Transliterator Компонент для генерации URL-алиасов. */
	protected static $_transliterator;

	/**
	 * Умная обрезка предложения до указанного размера, не обрезая слова (по знакам препинания или пробелам).
	 *
	 * @param string $string     Текст, который надо обрезать
	 * @param int    $max_length Максимальная длина строки, которую нельзя превышать
	 * @param string $endString  Окончание обрезанной строки
	 *
	 * @return string
	 *
	 * @author Залатов Александр <zalatov.ao@dns-shop.ru>
	 */
	public static function trimSentence($string, $max_length, $endString = '...') {
		if (mb_strlen($string) <= $max_length) { // Если длина строки меньше, чем надо, сразу возвращаем результат
			return $string;
		}

		$chars = '\s\?\.:;'; // Список символов, по которым можно обрезать строку
		$result = preg_replace('/[' . $chars . '][^' . $chars . ']+$/', '', mb_substr($string, 0, $max_length));

		return $result . $endString;
	}

	/**
	 *
	 * Возвращает форму слова, описывающего число
	 *
	 * @author Pabolkov D.
	 *
	 * @param int $num
	 *
	 * @return int
	 */
	public static function getCountPostfixForm($num) {
		$num = abs($num);
		$dec = $num % 10;
		$form = 0;
		if ($dec >= 2 && $dec <= 4) {
			$form = 1;
		}
		if ($dec == 0 || ($dec >= 5 && $dec <= 9) || ($num > 10 && $num < 20)) {
			$form = 2;
		}

		return $form;
	}

	/**
	 *
	 * Возвращает слово, форма которого соответствуюет указанному числу <$num>
	 *
	 * @author Pabolkov D.
	 *
	 * @param int    $num
	 * @param string $type []
	 *
	 * @return string
	 */
	public static function getCountPostfix($num, $type) {
		return $type[static::getCountPostfixForm($num)];
	}

	/** @var string трехбуквенный символ валюты рубля */
	const CURRENCY_RUB_SHORT = 'руб.';
	/** @var string однобуквенный символ валюты рубля */
	const CURRENCY_RUB_SHORTEST = 'р.';
	/** @var string международный символ валюты рубля в виде html-сущности */
	const CURRENCY_RUB_SIGN = '&#8381;';

	const CURRENCY_EUR_SIGN = '&#8364;';

	const CURRENCY_USD_SIGN = '&#36;;';

	/**
	 * отформатировать цену
	 *
	 * @param float  $price
	 * @param string $sign запись знака валюты (по-умолчанию - без знака валюты)
	 *
	 * @return string
	 */
	public static function formatPrice($price, $sign = null) {
		return number_format($price, 0, ',', ' ') . ($sign === null ? '' : ' ' . $sign);
	}

	/**
	 * отформатировать бонусные баллы
	 *
	 * @param float $bonus
	 *
	 * @return string
	 */
	public static function formatBonus($bonus) {
		return number_format($bonus, 0, '.', ' ');
	}

	/**
	 * отформатировать номер телефона
	 *
	 * @param int|string $phone номер телефона без форматирования в виде: 71111111111
	 *
	 * @return string номер телефона в виде +7 (111) 111-1111
	 */
	public static function formatPhone($phone) {
		return sprintf('+%s (%s) %s-%s', substr($phone, 0, 1), substr($phone, 1, 3), substr($phone, 4, 3), substr($phone, 7, 4));
	}

	/**
	 * Отформатировать номер телефона под формат в котором он указан в таблице ref_contractor_contact_information_1c
	 *
	 * @param int|string $phone номер телефона без форматирования в виде: 1111111111/1111111111
	 *
	 * @return string  номер телефона в виде +7(111)111 11 11
	 *
	 * @author Исаков Владислав <isakov.vi@dns-shop.ru>
	 */
	public static function formatPhoneContactInformation($phone) {
		return sprintf('+7(%s)%s %s %s', substr($phone, 0, 3), substr($phone, 3, 3), substr($phone, 6, 2), substr($phone, 8, 2));
	}

	/**
	 * Очистить форматирование у номера телефона (также устанавливает код страны 7 вместо 8 или если его нет).
	 *
	 * @see    \common\base\validators\PhoneValidator::isPhoneValid($phone, true) использовать для валидации номера телефона до применения этого метода.
	 *
	 * @param string $phone Номер телефона.
	 *
	 * @return string Номер телефона, начинающийся с 7, например: 71111111111
	 *
	 * @author Максим Трофимов <trofimov.mv@dns-shop.ru>
	 */
	public static function stripPhone($phone) {
		// оставляем только цифры
		$phone = preg_replace('/[^0-9]+/', '', $phone);

		// если нет ведущего кода страны (определяем по количеству цифр), то проставим
		if (strlen($phone) === 10) {
			$phone = '7' . $phone;
		}

		// меняем код страны с 8 на 7
		if (substr($phone, 0, 1) === '8') {
			$phone = '7' . substr($phone, 1, strlen($phone) - 1);
		}

		return $phone;
	}

	/**
	 * Вывод количества чего-то с окончанием согласно морфологии русского языка.
	 *
	 * countPostfix($AutoCnt, array ('товар', 'товара', 'товаров'), 'показывать 0 словом нет?', 'выводить перед словом число?')
	 *
	 * @param int          $count      Число
	 * @param array        $cases      Варианты слов с нужными окончаниями, например: array('товар', 'товара', 'товаров')
	 * @param string|false $zeroAsWord Показывать 0 каким-либо словом (например, "нет") или показывать как число 0 (false)
	 * @param bool         $showCount  Выводить перед словом число/количество (true) или нет (false)
	 *
	 * @return string
	 *
	 *
	 */
	public static function countPostfix($count, $cases, $zeroAsWord = 'нет', $showCount = true) {
		$countString = preg_replace('/[^\d]+/', '', $count);// Удаляем всё, кроме чисел

		// -- Определяем, какой тип окончания использовать
//		if ($countString == 0) {
//			$caseIndex = 2;
//		} else if (in_array(substr($countString, -2), array(11, 12, 13, 14))) {// Например, 11 (одиннадцать) товаров, ведь оканчиваясь на единицу будет неверно - 1 товар
//			$caseIndex = 2;
//		} else if ($countString == 1) {
//			$caseIndex = 0;
//		} else if (in_array(substr($countString, -1), array(2, 3, 4))) {// Например, 2 товара
//			$caseIndex = 1;
//		} else {
//			$caseIndex = 2;
//		}
		// -- -- -- --
		$caseIndex = self::getCountPostfixForm((int)$countString);

		$result = $cases[$caseIndex];

		// -- Если необходимо вывести перед словом количество
		if (false !== $showCount) {
			// -- Если количество равно нулю, и надо отобразить не число а слово (например, "нет")
			if (0 == $count && false !== $zeroAsWord) {
				$count = $zeroAsWord;
			}
			// -- -- -- --

			$result = $count . ' ' . $result;
		}

		// -- -- -- --

		return $result;
	}

	/**
	 * Отрендерить текст с метками для вставки значений вместо них.
	 *
	 * Предназначен для замены меток вида {title}, {modelName} в тексте некоторыми значениями.
	 *
	 * @param string   $template          Текст шаблона.
	 * @param string[] $templateVariables Данные для замены меток в шаблоне значениями, где ключ массива - название метки, а значение по ключу - значение для замены метки).
	 *
	 * @return string
	 *
	 *
	 *
	 */
	public static function renderTemplate($template, $templateVariables) {
		$template = str_replace('\\{', '&#123;', $template);
		$template = str_replace('\\}', '&#124;', $template);

		foreach ($templateVariables as $varName => $varValue) {
			$varName = preg_replace('/-|([A-Z])/', '(-)?$0', $varName);
			$template = preg_replace('/\{\s*(' . $varName . ')\s*\}/i', ($varValue ?: ''), $template);
		}

		return $template;
	}

	/**
	 * Получение url адреса исходя из заданного текста.
	 * Например, чтобы исходя из названия товара (или новости) отобразить человеко-понятный URL.
	 *
	 * @param string $text
	 *
	 * @return string
	 *
	 *
	 */
	public static function urlAlias($text) {
		$text = preg_replace('/\[([^\]]+)\]/u', '', $text);
		$text = preg_replace('/\(([^\)]+)\)/u', '', $text);

		$translit = [
			'/'  => '-',
			'\\' => '-',
			' '  => '-',
			'а'  => 'a',
			'б'  => 'b',
			'в'  => 'v',
			'г'  => 'g',
			'д'  => 'd',
			'е'  => 'e',
			'ё'  => 'yo',
			'ж'  => 'zh',
			'з'  => 'z',
			'и'  => 'i',
			'й'  => 'j',
			'к'  => 'k',
			'л'  => 'l',
			'м'  => 'm',
			'н'  => 'n',
			'о'  => 'o',
			'п'  => 'p',
			'р'  => 'r',
			'с'  => 's',
			'т'  => 't',
			'у'  => 'u',
			'ф'  => 'f',
			'х'  => 'x',
			'ц'  => 'c',
			'ч'  => 'ch',
			'ш'  => 'sh',
			'щ'  => 'shh',
			'ы'  => 'y',
			'э'  => 'e',
			'ю'  => 'yu',
			'я'  => 'ya',
			'ь'  => '',
			'ъ'  => '',
			'-'  => '-',
		];

		$text = mb_strtolower($text, 'UTF-8');

		$text = str_replace(array_keys($translit), array_values($translit), $text);

		$text = preg_replace('/[^\-_A-z0-9]/u', '', $text);
		$text = str_replace('-[]', '', $text);
		$text = str_replace('[', '', $text);
		$text = str_replace(']', '', $text);
		$text = trim($text, '-');
		$text = preg_replace('/-+/', '-', $text);

		return $text;
	}

	/**
	 *  Переводит первый символ в верхний регистр
	 *
	 * @param        $string
	 * @param string $e
	 *
	 * @return string
	 */
	public static function ucfirst($string, $e = 'utf-8') {
		if (function_exists('mb_strtoupper') && function_exists('mb_substr') && !empty($string)) {
			$string = mb_strtolower($string, $e);
			$upper = mb_strtoupper($string, $e);
			preg_match('#(.)#us', $upper, $matches);
			$string = $matches[1] . mb_substr($string, 1, mb_strlen($string, $e), $e);
		}
		else {
			$string = ucfirst($string);
		}

		return $string;
	}

	/**
	 * Конвертирует буквы строки английской раскладке в русскую и наоборот буквы русской раскладки в английскую
	 *
	 * @param string $string Конвертируемая строка
	 *
	 * @return bool Возвращает возвращает конвертированную строку
	 */
	public static function flipKeyboardLayout($string) {

		$from = [
			'`',
			'q',
			'w',
			'e',
			'r',
			't',
			'y',
			'u',
			'i',
			'o',
			'p',
			'[',
			']',
			'a',
			's',
			'd',
			'f',
			'g',
			'h',
			'j',
			'k',
			'l',
			';',
			'z',
			'x',
			'c',
			'v',
			'b',
			'n',
			'm',
			',',
			'.',
			'~',
			'й',
			'ц',
			'у',
			'к',
			'е',
			'н',
			'г',
			'ш',
			'щ',
			'з',
			'ф',
			'ы',
			'в',
			'а',
			'п',
			'р',
			'о',
			'л',
			'д',
			'я',
			'ч',
			'с',
			'м',
			'и',
			'т',
			'ь',
		];

		$to = [
			'ё',
			'й',
			'ц',
			'у',
			'к',
			'е',
			'н',
			'г',
			'ш',
			'щ',
			'з',
			'х',
			'ъ',
			'ф',
			'ы',
			'в',
			'а',
			'п',
			'р',
			'о',
			'л',
			'д',
			'ж',
			'я',
			'ч',
			'с',
			'м',
			'и',
			'т',
			'ь',
			'б',
			'ю',
			'Ё',
			'q',
			'w',
			'e',
			'r',
			't',
			'y',
			'u',
			'i',
			'o',
			'p',
			'a',
			's',
			'd',
			'f',
			'g',
			'h',
			'j',
			'k',
			'l',
			'z',
			'x',
			'c',
			'v',
			'b',
			'n',
			'm',
		];

		$equals = array_combine($from, $to);

		return (strtr($string, $equals));
	}

	/**
	 * Вырезает BB коды
	 *
	 * @param $text
	 *
	 * @return mixed
	 */
	public static function stripBBCode($text) {
		return preg_replace('/\[[^\]]{1,5}\]/', '', $text);
	}

	/**
	 * Преобразует BB коды в HTML
	 *
	 *
	 *
	 * @param string $text
	 *
	 * @return string
	 */
	public static function bbCodeToHtml($text) {
		$bbcode = [
			"#\\[table\\](.+?)\\[\\/table\\]#is",
			"#\\[tr\\](.+?)\\[\\/tr\\]#is",
			"#\\[td\\](.+?)\\[\\/td\\]#is",
			"#\\[b\\](.+?)\\[\\/b\\]#is",
			"#\\[i\\](.+?)\\[\\/i\\]#is",
			"#\\[u\\](.+?)\\[\\/u\\]#is",
			"#\\[code\\](.+?)\\[\\/code\\]#is",
			"#\\[quote\\](.+?)\\[\\/quote\\]#is",
			"#\\[url=(.+?)\\](.+?)\\[\\/url\\]#is",
			"#\\[url\\](.+?)\\[\\/url\\]#is",
			"#\\[img\\](.+?)\\[\\/img\\]#is",
			"#\\[size=(.+?)\\](.+?)\\[\\/size\\]#is",
			"#\\[color=(.+?)\\](.+?)\\[\\/color\\]#is",
			"#\\[list\\](.+?)\\[\\/list\\]#is",
			"#\\[list=(1|a|I)\\](.+?)\\[\\/list\\]#is",
			"#\\[\\*\\](.+?)\\[\\/\\*\\]#",
			"#\\[\\*\\](.+?)#",
			"#\\[email\\](.+?)\\[\\/email\\]#is",
		];
		/** @noinspection CssInvalidPropertyValue */
		$htmlcode = [
			"<table>\\1</table>",
			"<tr>\\1</tr>",
			"<td>\\1</td>",
			"<b>\\1</b>",
			"<i>\\1</i>",
			"<span style='text-decoration:underline'>\\1</span>",
			"<code class='code'>\\1</code>",
			"<table width = '95%'><tr><td>Цитата</td></tr><tr><td class='quote'>\\1</td></tr></table>",
			"<a href='\\1'>\\2</a>",
			"<a href='\\1'>\\1</a>",
			"<img src='\\1' alt = 'Изображение' />",
			"<span style='font-size:\\1%'>\\2</span>",
			"<span style='color:\\1'>\\2</span>",
			"<ul>\\1</ul>",
			"<ol type='\\1'>\\2</ol>",
			"<li>\\1</li>",
			"<li>\\1",
			"<a href=mailto:\\1>\\1</a>",
		];

		// -- Устранение дублирующих bb кодов
		$iterationCount = 0;
		$previousSize = strlen($text);
		while ($iterationCount < 10) {
			$iterationCount = $iterationCount + 1;
			$text = preg_replace($bbcode, $htmlcode, $text);
			if (strlen($text) === $previousSize) {
				break;
			}
			$previousSize = strlen($text);
		}
		// -- -- -- --

		$text = nl2br($text);//second pass

		return $text;
	}

	/**
	 * Обрабатывает исходный текст делая ссылки в нем активными
	 *
	 * @author kanatnikov a.
	 *
	 * @param string $text
	 *
	 * @return string
	 */
	public static function makeUrlsInTextActive($text) {
		// Регулярное выражение обнаруживающее ссылки
		$pattern = '/\b(?:(?:https?|ftp|file):\/\/|www\.|ftp\.)[-A-Z0-9\[\]\+\&\@\#\/\%\=\~\_\|\$\?\!\:\,\.;]*[A-Z0-9\[\]\+\&\@\#\/\%\=\~\_\|\$;]/i';

		$text = preg_replace($pattern, Html::a(' \0 ', '\0', ['rel' => 'nofollow noopener noreferrer', 'target' => '_blank']), $text);
		$text = preg_replace('/([^\s\+\-\/\<\>]{20})/su', '${1}&shy;', $text);

		return $text;
	}

	/**
	 * Удалить из Url параметр.
	 * Метод обрабатывает url с параметром для удаления, который сформирован как с помощью символа вопроса,
	 * так и с помощью амперсанда; также может удалить параметр из любой позиции в url.
	 *
	 * @param string $url       Url, где нужно удалить параметр.
	 * @param string $paramName Название параметра.
	 *
	 * @return string Url без параметра.
	 *
	 * @author Максим Трофимов <trofimov.mv@dns-shop.ru>
	 */
	public static function removeUrlParam($url, $paramName) {
		$parsedUrl = parse_url($url);
		$query = [];

		if (isset($parsedUrl['query'])) {
			parse_str($parsedUrl['query'], $query);
			unset($query[$paramName]);
		}

		$path = (isset($parsedUrl['path']) ? $parsedUrl['path'] : '');
		$query = (count($query) === 0 ? '' : '?' . http_build_query($query));

		return $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . $path . $query;
	}

	/**
	 * Сгенерировать UUID-идентификатор (v4) на основе переданной строки.
	 *
	 * @param string $identifier Строка, идентифицирующая некоторый объект, на основе которой будет сгенерирован UUID.
	 *
	 * @return string UUID-идентификатор, уникальность которого определяется уникальностью входного параметра.
	 *
	 * @author Максим Трофимов <trofimov.mv@dns-shop.ru>
	 */
	public static function generateUuid4($identifier) {
		// -- Так, на основе 32-значного хеша получаем 36-значный guid
		$hash = md5($identifier);
		$uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split($hash, 4));

		// -- -- --

		return $uuid;
	}

	/**
	 * Форматирует байты в кило/мега/гига байты.
	 *
	 * @param int $bytes     Размер в байтах
	 * @param int $precision Количество знаков после запятой
	 *
	 * @return string
	 *
	 * @author Канатников Андрей <kanatnikov.as@dns-shop.ru>
	 * @author Залатов Александр <zalatov.ao@dns-shop.ru>
	 */
	public static function formatBytes($bytes, $precision = 2) {
		$multiplier = ($bytes >= 0 ? 1 : -1);
		$bytes = abs($bytes);

		$base = log($bytes) / log(1024);
		$suffix = ['', 'Кб', 'Мб', 'Гб', 'Тб'][(int)floor($base)];

		return $multiplier * round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffix;
	}

	/**
	 * Конвертирование русской строки в транслит с помощью PECL intl.
	 *
	 * @param string $string Строка для конвертации
	 *
	 * @return string
	 *
	 * @author Залатов Александр <zalatov.ao@dns-shop.ru>
	 */
	public static function transliterate($string) {
		if (null === static::$_transliterator) {
			static::$_transliterator = Transliterator::create('Any-Latin; NFD; [:Nonspacing Mark:] Remove; Lower();');
		}

		return preg_replace('/[^0-9a-z\-_]/i', '', str_replace(' ', '-', static::$_transliterator->transliterate($string)));
	}

	/**
	 * Получение никнейма пользователя из его email адреса.
	 *
	 * @param string $email E-mail адрес
	 *
	 * @return string часть email адреса до символа '@' или входая строка без изменений.
	 *
	 * @author Турушев Николай <Turushev.NS@dns-shop.ru>
	 */
	public static function getUserNameFromEmail($email) {
		$emailValidator = new EmailValidator();

		if ($emailValidator->validate($email)) {
			$position = mb_strpos($email, "@");

			return mb_substr($email, 0, $position);
		}

		return $email;
	}

	/**
	 * Скрытие последних цифр номера телефона в исходной строке.
	 * Возвращает исходную строку с частично замененным номером телефона.
	 *
	 * @param string $inputString Входная строка
	 * @param int    $hideCount   Количество скрываемых цифр с конца номера
	 *
	 * @return string
	 *
	 * @author Турушев Николай <Turushev.NS@dns-shop.ru>
	 */
	public static function hidePartOfPhone($inputString, $hideCount = 3) {
		$phone = static::stripPhone($inputString);

		if (true !== PhoneValidator::isPhoneValid($phone)) {
			return $inputString;
		}

		return substr_replace($phone, str_repeat('*', $hideCount), $hideCount * -1, $hideCount);
	}

	/**
	 * Конвертация введённого номера документа в кириллицу (только визуально схожие буквы).
	 *
	 * @param string $docNumber Номер документа
	 *
	 * @return string номер документа
	 *
	 * @author Турушев Николай <Turushev.NS@dns-shop.ru>
	 */
	public static function translateDocNumber($docNumber) {
		$engLetters = ['A', 'B', 'C', 'E', 'H', 'K', 'M', 'O', 'P', 'T', 'X', 'Y'];
		$rusLetters = ['А', 'В', 'С', 'Е', 'Н', 'К', 'М', 'О', 'Р', 'Т', 'Х', 'У'];

		return str_replace($engLetters, $rusLetters, mb_strtoupper($docNumber));
	}

	/**
	 * Получение короткого uuid'а от guid'а.
	 *
	 * @param  string $guid Идентификатор
	 *
	 * @return string
	 *
	 * @author Медвеженков Владимир <medvezhenkov.v@dns-shop.ru>
	 */
	public static function guidToSearchUuid($guid) {
		return substr($guid, 0, 8) . substr($guid, 9, 4) . substr($guid, 31, 4);
	}

	/**
	 * Добавить атрибут target="_blank" для всех ссылок в контенте, кроме указанного домена
	 *
	 * @param string $content Контент
	 * @param string $domain  Домен
	 *
	 * @return string
	 *
	 * @author Трошин Андрей <troshin.av@dns-shop.ru>
	 */
	public static function addTargetBlankToLinksExcludeDomain($content, $domain) {
		$pattern = '~href\s*=[\s\"\']*(?:http|https):\/\/(?!' . $domain . '|www\.' . $domain . ')[^>]+~i';

		return preg_replace($pattern, 'target="_blank" $0', $content);
	}

	/**
	 * Метод удаляет лишние отступы от начала строки, если они не имеют смысла.
	 *
	 * Например, есть текст:
	 *     <div>
	 *         <span></span>
	 *     </div>
	 * В результате получится:
	 * <div>
	 *     <span></span>
	 * </div>
	 *
	 * @param string $text         Текст для обработки
	 * @param bool   $tabsToSpaces Переводить ли табы в пробелы, чтобы, например, во всех редакторах код выглядел одинаково
	 *
	 * @return string
	 *
	 * @author Залатов Александр <zalatov.ao@dns-shop.ru>
	 */
	public static function trimLeadSpace($text, $tabsToSpaces = false) {
		$text = explode("\n", $text);

		// -- Определяем минимальный отступ
		$minSpaces = 999;

		foreach ($text as $line) {
			// -- Пропускаем пустые строки
			if ('' === trim($line)) {
				continue;
			}
			// -- -- -- --

			$lengthB = mb_strlen($line);
			$line = ltrim($line);
			$lengthA = mb_strlen($line);

			$spacesCount = $lengthB - $lengthA;
			if ($spacesCount < $minSpaces) {
				$minSpaces = $spacesCount;
			}
		}
		// -- -- -- --

		// -- Удаляем лишнюю табуляцию и переводим табы в пробелы
		if (0 !== $minSpaces) {
			foreach ($text as $i => $line) {
				$line = mb_substr($line, $minSpaces);
				$line = rtrim($line);

				if (true === $tabsToSpaces) {
					$line = str_replace("\t", '    ', $line);
				}

				$text[$i] = $line;
			}
		}
		// -- -- -- --

		$text = implode("\n", $text);

		return $text;
	}
}