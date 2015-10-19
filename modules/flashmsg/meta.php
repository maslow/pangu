<?php
/**
 * Created by PhpStorm.
 * User: wangfugen
 * Date: 15/10/10
 * Time: 下午4:11
 */


return [
    'version' => '1.0',
    'description' => 'FlashMessage插件，将消息发送到下一个请求的页面。',

    'bootstrap' => true,
    'man' => false,
    'deps' => [
        'man',
        'member',
        'rbac',
    ],
];