<?php

namespace common\components;

use common\models\scheme\sns\AppMessages;
use common\models\scheme\sns\IOrderMessage;
use common\models\scheme\sns\IOrders;
use common\models\scheme\tour\Orders;
use Yii;
use yii\db\ActiveRecord;

/**
 *
 * Базовая ActiveRecord для моделей поддерживающих мультиязычность и историю поиска
 * В идеале все модели должны наследоваться от этой
 *
 * @author isakov.v
 */
class BileturActiveRecord extends ActiveRecord {

	/** @var UserRequestsHistory $historyRequest */
	public $historyRequest;

	public function init() {
		parent::init();
		$this->historyRequest = new UserRequestsHistory();
	}

	/**
	 * Переопределяем метод для получения лэйблов в зависимости от языка окружения
	 * @return mixed
	 */
	public function attributeLabels() {
		return $this->_getLabels();
	}

	/**
	 * Получение значений для вызывающего класса
	 *
	 * @return mixed
	 */
	private function _getLabels() {
		$labels = self::_labels();

		return $labels[get_called_class()][Yii::$app->env->getLanguage()];
	}

	/**
	 * Массив значений
	 *
	 * @return array
	 */
	private function _labels() {
		return [
			//Модель сообщения
			IOrderMessage::className() => [
				'ru' => [
					'MSG'       => 'Сообщение',
					'ORDID'     => '',
					'ordSecret' => ''
				],
				'en' => [
					'MSG'       => 'Message',
					'ORDID'     => '',
					'ordSecret' => ''
				]
			],
			AppMessages::className()   => [
				'ru' => [
					'MSG' => 'Сообщение',
				],
				'en' => [
					'MSG' => 'Message',
				]
			],
			IOrders::className()       => [
				'ru' => [
					'rulesAccepted' => 'Подтверждаю правильность данных заказа и согласие с условиями оформления билетов'
				],
				'en' => [
					'rulesAccepted' => 'Подтверждаю правильность данных заказа и согласие с условиями оформления билетов'
				]
			],
			Orders::className()        => [
				'ru' => [
					'rulesAccepted' => ''
				],
				'en' => [
					'rulesAccepted' => ''
				]
			],
		];
	}
}