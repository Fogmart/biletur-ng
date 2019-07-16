<?php

use common\modules\pages\components\StaticPageUrlRule;

$params = array_merge(
	require __DIR__ . '/../../common/config/params.php',
	require __DIR__ . '/../../common/config/params-local.php',
	require __DIR__ . '/params.php',
	require __DIR__ . '/params-local.php'
);

$modulesUrlRules = array_merge(
	require __DIR__ . '/../../common/modules/banner/config/url-rules.php',
	require __DIR__ . '/../../common/modules/api/config/url-rules.php',
	require __DIR__ . '/../../common/modules/message/config/url-rules.php',
	require __DIR__ . '/../../common/modules/news/config/url-rules.php',
	require __DIR__ . '/../../common/modules/order/config/url-rules.php',
	require __DIR__ . '/../../common/modules/pages/config/url-rules.php',
	require __DIR__ . '/../../common/modules/profile/config/url-rules.php',
	require __DIR__ . '/../../common/modules/seo/config/url-rules.php'
);

$commonUrlRules = [
	''                   => 'site/index',
	'sitemap.xml'        => 'sitemap/default/index',
	[
		'pattern' => 'page',
		'route'   => 'page',
		'class'   => StaticPageUrlRule::class,
	],
	[
		'pattern' => 'sitemap',
		'route'   => 'sitemap/default/index',
		'suffix'  => '.xml'
	],
	'site/set-city/<id>' => '/site/set-city/',
	'thumbs/<path:.*>'   => 'site/thumb',

	'excursion/find-by-name/<q>/<needType>' => 'excursion/find-by-name',
	'excursion/widget/<city>'               => 'excursion/widget',
	'excursion/widget/<city>/<needSearch>'  => 'excursion/widget',
	'excursion/city/<city>'                 => 'excursion/index',

	'tour/search'            => 'tour/index/',
	'tour/<id>/<src>/<slug>' => 'tour/view/',
	'tour/<id>/<src>'        => 'tour/view/',

	'login'   => 'site/login',

	'<module:[\w-]+>/<controller:[\w-]+>/<action:[\w-]+>/<id:\d+>]' => '<module>/<controller>/<action>',
	'<controller:\w+>/<action:\w+>/'                                => '<controller>/<action>',
	'hotels/find-by-name/q/<q>'                                     => 'hotels/find-by-name',

	//Обработка старых ссылок------------------------------------------------------------
	'Agency'                                                        => 'old-links/agency',//static
	'Agency/index.asp'                                              => 'old-links/agency',//static
	'TimeTbl'                                                       => 'old-links/avia',
	'TimeTbl/pkc/delays.asp'                                        => 'old-links/avia',
	'TimeTbl/vvo/delays.asp'                                        => 'old-links/avia',
	'Passenger/vvo/railway_schedule.asp'                            => 'old-links/rail-road',
	'Agency/Rekvizit.asp'                                           => 'old-links/accounts',//static
	'Agency/foradvertisers.asp'                                     => 'old-links/advertising',//static
	'Tourism/tour.asp'                                              => 'old-links/tour',
	'tourism/tour.asp'                                              => 'old-links/tour',
	'tourism/hotel/'                                                => 'old-links/hotels',
	'order/ordPayInfo.asp'                                          => 'old-links/ord-pay-info',//static
	'BackCall/BackCall.asp'                                         => 'old-links/back-call',//dynamic form
	'Agency/vacancy.asp'                                            => 'old-links/vacancy',//dynamic
	'avianet/default.asp'                                           => 'old-links/partners',//static
	'Agency/structura/index.asp'                                    => 'old-links/agency-struct',//static
	'Agency/Filials/default.asp'                                    => 'old-links/filials',//dynamic
	'Agency/Commendations.asp'                                      => 'old-links/commendations',//static
	'Agency/awards.asp'                                             => 'old-links/awards', //static
	//---------------------------------------------------------------------------------------
];

return [
	'id'                  => 'app-frontend',
	'name'                => 'Всероссийская сеть Билетур',
	'basePath'            => dirname(__DIR__),
	'bootstrap'           => ['log'],
	'controllerNamespace' => 'frontend\controllers',
	'components'          => [
		'env'          => [
			'class'              => common\components\Environment::class,
			'defaultCityId'      => '_1CK0R7WDW',
			'defaultLanguage'    => 'ru',
			'defaultAirportCode' => 'VVO',
			'defaultArrCityId'   => '957979',
			'defaultTourZone'    => 3
		],
		'request'      => [
			'csrfParam' => '_csrf-frontend',
			'baseUrl'   => '',
		],
		'user'         => [
			'identityClass'   => 'common\models\User',
			'enableAutoLogin' => true,
			'identityCookie'  => ['name' => '_identity-biletur', 'httpOnly' => true],
		],
		'session'      => [
			'name' => 'biletur-session',
		],
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
		'urlManager'   => [
			'enablePrettyUrl' => true,
			'showScriptName'  => false,
			'suffix'          => '/',
			'rules'           => array_merge($commonUrlRules, $modulesUrlRules)
		],
	],
	'params'              => $params,
];
