<?php

namespace common\modules\message\controllers;

use common\base\helpers\DateHelper;
use common\components\FrontendController;
use common\modules\message\models\Message;
use yii\db\Expression;

/**
 *
 * @author  Исаков Владислав <visakov@biletur.ru>
 */
class MessageController extends FrontendController {

	/**
	 * Отрисовка виджета добавления сообщений к обьекту
	 *
	 * @param string $object
	 * @param string $objectId
	 * @param string $userName
	 *
	 * @return string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function actionWidget($object, $objectId, $userName) {
		$this->layout = 'widget';

		$errors = [];
		if (\Yii::$app->request->isPjax) {
			$newMessage = new Message();
			$newMessage->load(\Yii::$app->request->post());
			$newMessage->insert_stamp = new Expression('sysdate');
			$newMessage->update_stamp = new Expression('sysdate');

			if ($newMessage->validate()) {
				$newMessage->save();
			}
			else {
				$errors = $newMessage->getErrors();
			}
		}

		$messages = Message::find()
			->where([Message::ATTR_OBJECT => $object, Message::ATTR_OBJECT_ID => $objectId])
			->orderBy([Message::ATTR_INSERT_STAMP => SORT_DESC])
			->all();

		$messageForm = new Message();
		$messageForm->object = $object;
		$messageForm->object_id = $objectId;
		$messageForm->user_name = $userName;


		return $this->render('widget', [
				'errors'   => $errors,
				'messages' => $messages,
				'model'    => $messageForm,
				'userName' => $userName
			]
		);
	}
}