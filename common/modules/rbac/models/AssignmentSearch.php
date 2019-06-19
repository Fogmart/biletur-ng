<?php

namespace common\modules\rbac\models;

use common\models\User;
use common\modules\rbac\Module;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * @author John Martin <john.itvn@gmail.com>
 * @since  1.0.0
 *
 */
class AssignmentSearch extends \yii\base\Model {

	/**
	 * @var Module $rbacModule
	 */
	protected $rbacModule;

	/**
	 *
	 * @var mixed $id
	 */
	public $id;

	/**
	 *
	 * @var string $login
	 */
	public $username;

	/**
	 * @inheritdoc
	 */
	public function init() {
		parent::init();
		$this->rbacModule = Yii::$app->getModule('rbac');
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['id', 'username'], 'safe'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'id'    => Yii::t('rbac', 'ID'),
			'username' => $this->rbacModule->userModelLoginFieldLabel,
		];
	}

	/**
	 * Create data provider for Assignment model.
	 */
	public function search() {
		$query = call_user_func($this->rbacModule->userModelClassName . "::find");
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);
		$params = Yii::$app->request->getQueryParams();

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

		$query->andFilterWhere([User::tableName() . '.' . User::ATTR_ID => $this->id]);
		$query->andFilterWhere(['like', User::tableName() . '.' . User::ATTR_USER_NAME, $this->username]);

		return $dataProvider;
	}

}
