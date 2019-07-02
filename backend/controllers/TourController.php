<?php
namespace backend\controllers;

use common\components\tour\CommonTour;
use common\forms\tour\SearchForm;
use common\models\oracle\scheme\t3\RefItems;
use common\modules\rbac\rules\Permissions;
use common\modules\seo\models\Seo;
use yii\filters\AccessControl;
use Yii;
use yii\helpers\Url;

/**
 * Контроллер добавление доп.инфо для туров
 *
 * @package backend\controllers
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class TourController extends BackendController {
	const ACTION_INDEX = 'index';
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

	/**
	 * @param int $id
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 *
	 * @return string
	 */
	public function actionUpdate($id) {
		$seo = Seo::findOne([Seo::ATTR_OBJECT => CommonTour::class, Seo::ATTR_OBJECT_ID => $id]);
		if (null === $seo) {
			$seo = new Seo();
			$seo->object = CommonTour::class;
			$seo->object_id = $id;
		}

		if (Yii::$app->request->isPost) {
			$seo->load(Yii::$app->request->post());
			$seo->url = '0';
			$seo->user_id = Yii::$app->user->id;
			$seo->save(false);
		}

		$refItem = RefItems::findOne([RefItems::ATTR_ID => $id]);

		Url::remember();

		return $this->render('update', ['seo' => $seo, 'refItem' => $refItem]);
	}
}
