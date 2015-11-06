<?php

namespace app\modules\rbac;

class Module extends \yii\base\Module
{
    const EVENT_BEFORE_CREATE_ROLE = 'beforeCreateRole';
    const EVENT_CREATE_ROLE_SUCCESS = 'createRoleSuccess';
    const EVENT_CREATE_ROLE_FAIL ='createRoleFail';

    const EVENT_BEFORE_UPDATE_ROLE = 'beforeUpdateRole';
    const EVENT_UPDATE_ROLE_SUCCESS = 'updateRoleSuccess';
    const EVENT_UPDATE_ROLE_FAIL = 'updateRoleFail';

    const EVENT_BEFORE_DELETE_ROLE = 'beforeDeleteRole';
    const EVENT_DELETE_ROLE_SUCCESS= 'deleteRoleSuccess';
    const EVENT_DELETE_ROLE_FAIL = 'deleteRoleFail';

    const EVENT_PERMISSION_REQUIRED = 'permissionRequired';
    public $controllerNamespace = 'app\modules\rbac\controllers';

    public function init()
    {
        parent::init();
        // custom initialization code goes here
    }
}
