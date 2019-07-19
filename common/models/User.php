<?php

namespace common\models;

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

	const ATTR_ID = 'id';
	const ATTR_USER_NAME = 'username';
	const ATTR_PASSWORD_HASH = 'password_hash';
	const ATTR_STATUS = 'status';

	public $identityClass;
	public $loginUrl;

	/**
	 * {@inheritdoc}
	 */
	public static function findIdentityByAccessToken($token, $type = null) {
		return static::findOne(['password_hash' => $token]);
	}
}
