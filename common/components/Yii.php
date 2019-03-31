<?php
/**
 *
 * Этот файл содержит phpdoc для системных классов Yii.
 * Внимание! Он не подключается и не используется для создания объектов.
 *
 * Этот файл нужен только для IDE как "обманка", чтобы не править файлы в папке vendor.
 * Так как базовая версия Yii не значет ничего о нашиъ компонентах и наших классах, то IDE не подсвечивает их.
 * Чтобы это работало, в этом файле мы переопределили Yii и Application, которые будем дополнять своими свойствами.
 *
 * Возможно, потребуется пометить оригинальный Yii.php как PlainText, чтобы автокомплит полноценно работал.
 */

class Yii extends \yii\BaseYii {
	/** @var yii\console\Application|yii\web\Application|Application The application instance */
	public static $app;
}

/**
 * @author isakov.v
 *
 * @property common\components\Environment      $env
 * @property yii\swiftmailer\Mailer             $mail
 * @property common\components\RemoteImageCache $remoteImageCache
 * @property common\components\BileturMail      $bileturMail
 */
class Application {
}