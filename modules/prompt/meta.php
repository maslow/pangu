<?php

return [
    'version' => '1.0',
    'description' => '基于Yii Flash Session的信息提示插件，将消息发送到下一个请求的页面。',

    'bootstrap' => true,
    'manager' => false,
    'deps' => [
        'backend',
        'member',
        'rbac',
    ],
];