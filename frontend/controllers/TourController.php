<?php

namespace frontend\controllers;

use common\components\FrontendMenuController;
use common\components\tour\CommonTour;
use common\forms\tour\SearchForm;
use common\base\helpers\StringHelper;
use common\modules\seo\models\Seo;
use Yii;

/**
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class TourController extends FrontendMenuController {

	const ACTION_INDEX = 'index';
	const ACTION_VIEW = 'view';

	/**
	 * Точка входа Туры
	 *
	 * @return string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionIndex() {
		$form = new SearchForm();

		if (Yii::$app->request->isPjax) {
			$form->load(Yii::$app->request->post());
		}

		$form->search();

		return $this->render('index', ['form' => $form]);
	}


	/**
	 * Карточка тура
	 *
	 * @param string $id
	 * @param int    $src Источник туры
	 * @param string $slug
	 *
	 * @return string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionView($id, $src = CommonTour::SOURCE_BILETUR, $slug = null) {
		$commonTour = new CommonTour([CommonTour::ATTR_SOURCE => $src, CommonTour::ATTR_SOURCE_ID => $id]);
		$commonTour->prepare();

		//Регистрируем метатэги
		Seo::registerMetaByObject(CommonTour::class, $commonTour->sourceId, $this->view);

		//Регистрируем каноническую ссылку для поисковиков, чтобы старые ссылки без slug не конфликтовали в индексации
		$this->view->registerLinkTag([
				'rel'  => 'canonical',
				'href' => Yii::$app->request->hostInfo . static::getActionUrl(static::ACTION_VIEW, ['id' => $id, 'src' => $src, 'slug' => StringHelper::urlAlias($commonTour->title)])
			]
		);

		return $this->render('view', ['tour' => $commonTour]);
	}
}