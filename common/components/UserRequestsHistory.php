<?php

namespace common\components;

use Yii;
use yii\base\Component;

/**
 *
 * Класс сохранения и получения последних запросов пользователей к моделям
 *
 * @author isakov.v
 */
class UserRequestsHistory extends Component {
	/**
	 * @param array $data
	 */
	public function setRequestToHistory($data) {
		$className = get_called_class();
		Yii::$app->memcache->set($this->_getName($className), $data, 60 * 60);
	}

	/**
	 * @param string $className
	 *
	 * @return string
	 */
	private function _getName($className) {
		return 'request_history.' . $className . '.' . Yii::$app->request->cookies['_csrf'];
	}

	/**
	 * @return array()
	 */
	public function getRequestFromHistory() {
		$className = get_called_class();
		$requestName = $this->_getName($className);

		return Yii::$app->memcache->get($requestName);
	}
}