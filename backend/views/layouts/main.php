<?php

/* @var $this \yii\web\View */

/* @var $content string */

use backend\assets\AppAsset;
use backend\controllers\LogController;
use common\modules\banner\controllers\BannerController;
use common\modules\pages\controllers\PageController;
use common\modules\seo\controllers\SeoController;
use common\widgets\Alert;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
	<?php
	NavBar::begin([
		'brandLabel' => Yii::$app->name,
		'brandUrl'   => Yii::$app->homeUrl,
		'options'    => [
			'class' => 'navbar-inverse navbar-fixed-top',
		],
	]);
	$menuItems = [
		[
			'label'   => 'Пользователи',
			'visible' => !Yii::$app->user->isGuest,//Yii::$app->user->can(Permissions::P_ADMIN, [], false),
			'items'   => [
				[
					'label' => 'Роли',
					'url'   => ['/rbac/role'],
				],
				[
					'label' => 'Правила',
					'url'   => ['/rbac/rule'],
				],
				[
					'label' => 'Разрешения',
					'url'   => ['/rbac/permission'],
				],
				[
					'label' => 'Назначение ролей',
					'url'   => ['/rbac/assignment'],
				],
			]
		],
		[
			'label' => 'Ошибки сайта', 'url' => LogController::getActionUrl(LogController::ACTION_INDEX)
		],
		[
			'label' => 'Контент',
			'items' => [
				['label' => 'Страницы', 'url' => PageController::getActionUrl(PageController::ACTION_INDEX)],
				['label' => 'Настройки SEO', 'url' => SeoController::getActionUrl(SeoController::ACTION_INDEX)],
				['label' => 'Баннеры', 'url' => BannerController::getActionUrl(BannerController::ACTION_INDEX)],
			],
		],
	];

	if (Yii::$app->user->isGuest) {
		$menuItems[] = ['label' => 'Войти', 'url' => ['/site/login']];
	}
	else {
		$menuItems[] = '<li>'
			. Html::beginForm(['/site/logout'], 'post')
			. Html::submitButton(
				'Выход (' . Yii::$app->user->identity->username . ')',
				['class' => 'btn btn-link logout']
			)
			. Html::endForm()
			. '</li>';
	}
	echo Nav::widget([
		'options' => ['class' => 'navbar-nav navbar-right'],
		'items'   => $menuItems,
	]);
	NavBar::end();
	?>

    <div class="container">
		<?= Breadcrumbs::widget([
			'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
		]) ?>
		<?= Alert::widget() ?>
		<?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
