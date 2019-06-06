<?php

namespace common\models;

use common\base\helpers\Dump;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string  $username
 * @property string  $password_hash
 * @property string  $password_reset_token
 * @property string  $verification_token
 * @property string  $email
 * @property string  $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string  $password write-only password
 */
class User extends \mdm\admin\models\User implements IdentityInterface {

	public function getPrimaryKey($asArray = false) {
		return 'id';
	}

	/**
	 * {@inheritdoc}
	 */
	public static function findIdentityByAccessToken($token, $type = null) {
		return static::findOne(['password_hash' => $token]);
	}
}
