<?php

namespace common\modules\banner\controllers;

use backend\controllers\BackendController;
use common\base\helpers\Dump;
use common\modules\banner\models\Banner;
use common\modules\banner\models\SearchBanner;
use common\modules\pages\models\Page;
use common\modules\pages\models\SearchPage;
use Yii;
use yii\caching\TagDependency;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;

/**
 * Контроллер баннер
 */
class BannerController extends BackendController {
	const ACTION_INDEX = '';

	/**
	 * {@inheritdoc}
	 */
	public function behaviors() {
		return [
			'verbs'  => [
				'class'   => VerbFilter::class,
				'actions' => [
					'delete' => ['POST'],
				],
			],
		];
	}

	/**
	 * Lists all Page models.
	 * @return mixed
	 */
	public function actionIndex() {
		$searchModel = new SearchBanner();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single model.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionView($id) {
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate() {
		$model = new Banner();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			TagDependency::invalidate(Yii::$app->cache, [Banner::class]);
			return $this->redirect(['index']);
		}

		return $this->render('create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionUpdate($id) {
		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			TagDependency::invalidate(Yii::$app->cache, [Page::class]);

			return $this->redirect(['update', 'id' => $model->id]);
		}

		Url::remember();

		return $this->render('update', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing Banner model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionDelete($id) {
		TagDependency::invalidate(Yii::$app->cache, [Banner::class]);
		$this->findModel($id)->delete();

		return $this->redirect(['index']);
	}

	/**
	 * Finds the Page model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return Banner the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = Banner::findOne($id)) !== null) {
			return $model;
		}

		throw new NotFoundHttpException('The requested page does not exist.');
	}
}
