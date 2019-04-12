<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\pages\models\SearchPage */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pages';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
		<?= Html::a('Create Page', ['create'], ['class' => 'btn btn-success']) ?>
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
			//'seo_title',
			//'seo_description',
			// 'seo_keywords',
			'slug',
			//'html:ntext',
			'is_published',
			//'insert_stamp',
			//'update_stamp',

			['class' => 'yii\grid\ActionColumn'],
		],
	]); ?>

	<?php Pjax::end(); ?>

</div>
