<?php

namespace app\base;

/**
 * Class CheckAccessTrait
 * @package app\base
 */
trait CheckAccessTrait
{
    /**
     * 判断当前登录管理员是否满足指定权限
     * @param $permission string 指定权限名
     * @return boolean
     */
    protected function checkAccess($permission)
    {
        /* @var $manager \yii\web\User */
        $manager = \Yii::$app->manager;

        if (!$manager->can($permission)) {
            $e = new \app\base\Event();
            $e->permission = $permission;
            $e->sender = $this;
            \Yii::$app->trigger('events.backend.access.reject',$e);
            return false;
        }

        return true;
    }

    /**
     * 用户无权限访问请求时，跳转到指定页面
     * @return \yii\web\Response
     */
    protected function goNotAllowed()
    {
        return $this->redirect(\Yii::$app->params['backend.default.page']);
    }
}