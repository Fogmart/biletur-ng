<?php
namespace frontend\controllers;

use common\modules\pages\models\Page;

/**
 * Контроллер статических страниц
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class StaticPageController {

	/**
	 * @param int $id
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionIndex($id) {
		/** @var Page $page */
		$page = Page::find()->where([Page::ATTR_ID => $id, Page::ATTR_IS_PUBLISHED => true])->one();

		echo $page->html;
	}
}