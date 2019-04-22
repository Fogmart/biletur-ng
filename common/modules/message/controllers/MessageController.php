<?php
namespace common\modules\message\controllers;

use common\components\FrontendController;
use common\modules\message\models\Message;
use Yii;
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
		$cacheKey = Yii::$app->cache->buildKey([$object, $objectId]);

		$errors = [];
		$isNewMessage = false;
		if (Yii::$app->request->isPjax && Yii::$app->request->post('Message') != []) {
			$newMessage = new Message();
			$newMessage->load(\Yii::$app->request->post());
			$newMessage->insert_stamp = new Expression('sysdate');
			$newMessage->update_stamp = new Expression('sysdate');
			$newMessage->message = $newMessage->message;
			$newMessage->user_name = $newMessage->user_name;

			Yii::$app->cache->delete($cacheKey);

			if ($newMessage->validate()) {
				$newMessage->save();
			}
			else {
				$errors = $newMessage->getErrors();
			}
		}

		$messages = Yii::$app->cache->get($cacheKey);
		if (false === $messages) {
			/** @var \common\modules\message\models\Message[] $messages */
			$messages = Message::find()
				->where([Message::ATTR_OBJECT => $object, Message::ATTR_OBJECT_ID => $objectId])
				->orderBy([Message::ATTR_INSERT_STAMP => SORT_DESC])
				->all();

			foreach ($messages as $message) {
				if ($message->user_name == $userName) {
					$message->isMine = true;
				}
			}
			$isNewMessage = true;
			Yii::$app->cache->set($cacheKey, $messages, null);
		}

		$messageForm = new Message();
		$messageForm->object = $object;
		$messageForm->object_id = $objectId;
		$messageForm->user_name = $userName;

		return $this->render('widget', [
				'errors'       => $errors,
				'messages'     => $messages,
				'model'        => $messageForm,
				'userName'     => $userName,
				'isNewMessage' => $isNewMessage
			]
		);
	}
}