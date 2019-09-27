<?php
namespace common\modules\api\etm\components;

use yii\base\Component;

/**
 * @author Исаков Владислав <visakov@biletur.ru>
 */
class EtmApi extends Component {
	public $url;
	public $apiKey;

	const METHOD_SEARCH = 'search';

	/**
	 * @param string $method
	 * @param string $query
	 * @param bool   $isPost
	 *
	 * @return mixed
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function sendRequest($method, $query, $isPost) {
		$url = $this->url . $method;
		$query = json_encode($query);

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_POST, $isPost);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
		curl_setopt($curl, CURLOPT_HEADER, [
			'Content-Type: application/json',
			'Content-Length: ' . strlen($query),
			'etm-auth-key: ' . $this->apiKey
		]);

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$out = curl_exec($curl);
		curl_close($curl);

		return json_decode($out);
	}
}