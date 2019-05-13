<?php


use yii\BaseYii;
use yii\swiftmailer\Mailer;

/**
 * Этот файл содержит phpdoc для системных классов Yii.
 * Внимание! Он не подключается и не используется для создания объектов.
 *
 * Этот файл нужен только для IDE как "обманка", чтобы не править файлы в папке vendor.
 * Так как базовая версия Yii не значет ничего о наших компонентах и наших классах, то IDE не подсвечивает их.
 * Чтобы это работало, в этом файле мы переопределили Yii и Application, которые будем дополнять своими свойствами.
 *
 * Возможно, потребуется пометить оригинальный Yii.php как PlainText, чтобы автокомплит полноценно работал.
 */
class Yii extends BaseYii {
	/** @var yii\console\Application|yii\web\Application|Application The application instance */
	public static $app;
}

/**
 *
 * @property-read \common\components\SiteConnection                   $db
 * @property-read \common\components\DspConnection                    $dbDsp
 * @property-read Mailer                                              $mailer
 * @property-read \common\components\IpGeoBase                        $ipgeobase
 * @property-read \common\components\Environment                      $env
 * @property-read \common\modules\api\ostrovok\components\OstrovokApi $ostrovokApi
 * @property-read \common\modules\api\tripster\components\TripsterApi $tripsterApi
 * @property-read \yii\mongodb\Connection                             $mongodb
 */
class Application {
}

// -- Эта часть кода взята из файла, где находится оригинальный класс Yii
spl_autoload_register(['Yii', 'autoload'], true, true);
Yii::$classMap = require(dirname(dirname(__DIR__)) . '/vendor/yiisoft/yii2/classes.php');
Yii::$container = new yii\di\Container();
// -- -- -- --