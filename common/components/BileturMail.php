<?php

namespace common\components;

use Yii;
use yii\base\Component;

/**
 * @author isakov.v
 *
 *
 */
class BileturMail extends Component {
	/**
	 * Отправка сообшения на почту
	 *
	 * @param string $sendTo
	 * @param array  $sendFrom
	 * @param string $subject
	 * @param string $body
	 */
	public function send($sendTo, $sendFrom, $subject, $body) {
		Yii::$app->mail->compose()
			->setTo($sendTo)
			->setFrom($sendFrom)
			->setSubject($subject)
			->setTextBody($body)
			->send();
	}
}