<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\pages\models\SearchPage */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Страницы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
		<?= Html::a('Новая страница', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

	<?php Pjax::begin(); ?>
	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			'id',
			'title',
			'slug',
			[
				'attribute' => 'is_published',
				'value'     => function ($data) {
					return $data->is_published === 1 ? 'Да' : 'Нет';
				},
			],
			'insert_stamp',
			'update_stamp',

			['class' => 'yii\grid\ActionColumn'],
		],
	]); ?>

	<?php Pjax::end(); ?>

</div>
