<?php
namespace common\components;

use yii\db\Expression;

class OracleDbExpression extends Expression {
	public function load(){
		return $this->expression;
	}
}