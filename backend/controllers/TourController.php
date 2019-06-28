<?php

namespace backend\controllers;

use common\forms\tour\SearchForm;
use common\modules\rbac\rules\Permissions;
use yii\filters\AccessControl;
use Yii;

/**
 * Контроллер добавление доп.инфо для туров
 *
 * @package backend\controllers
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class TourController extends BackendController {
	const ACTION_INDEX = '';
	const ACTION_UPDATE = 'update';

	/**
	 * {@inheritdoc}
	 */
	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::class,
				'rules' => [
					[
						'actions' => [
							'index',
							static::ACTION_INDEX,
							static::ACTION_UPDATE,
						],
						'allow'   => true,
						'roles'   => [Permissions::ROLE_ADMIN],
					],
				],
			],
		];
	}

	/**
	 * @return string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionIndex() {
		$form = new SearchForm();

		if (Yii::$app->request->isPost) {
			$form->load(Yii::$app->request->post());
			$form->search(true);
		}

		return $this->render('index', ['form' => $form]);
	}
}
