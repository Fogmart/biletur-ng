<?php

namespace common\components\widgets;

use yii\base\Widget;

/**
 * @author isakov.v
 *
 *
 */
class SocialWidget extends Widget {

	public function init() {
		parent::init();

	}

	public function run() {
		return $this->render('social');
	}
}