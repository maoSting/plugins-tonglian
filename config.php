<?php
/**
 * Created by PhpStorm.
 * Author: DQ
 * Date: 2019/8/19
 * Time: 10:29
 */
return [
    'user_prefix'   => [
        'title' => '帐号前缀',
        'type'  => 'text',
        'value' => 'tl_',
        'tip'   => '账号前缀，避免帐号冲突',
    ],
    'user_info_url' => [
        'title' => '用户信息URL',
        'type'  => 'text',
        'value' => '',
        'tip'   => '通过接口请求用户信息',
    ],
    'user_role'        => [
        'title' => '用户角色',
        'type'  => 'number',
        'value' => '2',
        'tip'   => '角色ID',
    ],
];