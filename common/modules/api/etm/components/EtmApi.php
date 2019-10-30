<?php

namespace common\modules\api\etm\components;

use common\base\helpers\Dump;
use Yii;
use yii\base\Component;
use yii\base\Configurable;

/**
 * @author Исаков Владислав <visakov@biletur.ru>
 */
class EtmApi extends Component implements Configurable {
	private $_url;
	private $_apiKey;

	const METHOD_SEARCH = 'search';
	const METHOD_OFFERS = 'offers';
	const METHOD_AIRLINES = 'airlines';

	/**
	 * @param array $config
	 *
	 * @author Isakov Vlad <visakov@biletur.ru>
	 *
	 * TripsterApi constructor.
	 */
	public function __construct($config = []) {
		if (!empty($config)) {
			Yii::configure($this, $config);
		}
		parent::__construct($config);
	}

	/**
	 * @param string $value
	 *
	 * @return $this
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function setUrl($value) {
		$this->_url = $value;

		return $this;
	}

	/**
	 * @param string $value
	 *
	 * @return $this
	 *
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function setApiKey($value) {
		$this->_apiKey = $value;

		return $this;
	}

	/**
	 * @param string $method
	 * @param string $query
	 * @param bool   $isPost
	 *
	 * @return mixed
	 * @author Исаков Владислав <visakov@biletur.ru>
	 */
	public function sendRequest($method, $query, $isPost) {
		$url = $this->_url . $method;
		$query = json_encode($query);

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_POST, $isPost);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
		curl_setopt($curl, CURLOPT_HEADER, [
			'Content-Type: application/json',
			'Content-Length: ' . strlen($query),
			'etm-auth-key: ' . $this->_apiKey
		]);

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$out = curl_exec($curl);
		curl_close($curl);

		Dump::dDie($out);

		return json_decode($out);
	}
}