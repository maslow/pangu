<?php

return [
    'version' => '1.0',
    'description' => 'FlashMessage插件，将消息发送到下一个请求的页面。',

    'bootstrap' => true,
    'manager' => false,
    'deps' => [
        'man',
        'member',
        'rbac',
    ],
];