<?php
/**
 * @author Исаков Владислав <visakov@biletur.ru>
 */

namespace common\components;


use yii\base\Component;

class Sms extends Component {

	public $url = '';
	const ATTR_URL = 'login';

	public $action = '';
	const ATTR_ACTION = 'action';

	public $login = '';
	const ATTR_LOGIN = 'login';

	public $password = '';
	const ATTR_PASSWORD = 'password';

	public $text;
	const ATTR_TEXT = 'text';

	public $phone;
	const ATTR_PHONE = 'phone';

	const ACTION_SEND = 'send';
	const ACTION_STATUS = 'status';

	/**
	 * Отправка запроса
	 *
	 * @return bool|string
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function send() {
		$url = $this->url;

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$out = curl_exec($curl);
		$result = $out;
		curl_close($curl);

		return $result;
	}
}