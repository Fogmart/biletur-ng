<?php
namespace common\modules\rbac\models;

use Yii;
use yii\rbac\Item;

/**
 * Description of Permistion
 *
 * @author John Martin <john.itvn@gmail.com>
 * @since 1.0.0
 */
class Permission extends AuthItem {

	const PERMISSION_ADMIN = 'PermissionAdmin';
	const PERMISSION_SEO_EDITOR = 'PermissionSeoEditor';
	const PERMISSION_STATIC_PAGE_EDITOR = 'PermissionStaticPageEditor';

    protected function getType() {
        return Item::TYPE_PERMISSION;
    }

    public function attributeLabels() {
        $labels = parent::attributeLabels();
        $labels['name'] = Yii::t('rbac', 'Permission name');
        return $labels;
    }

    public static function find($name) {
        $authManager = Yii::$app->authManager;
        $item = $authManager->getPermission($name);
        return new self($item);
    }

}
