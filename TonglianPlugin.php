<?php
/**
 * Created by PhpStorm.
 * Author: DQ
 * Date: 2019/8/19
 * Time: 10:40
 */

namespace plugins\tonglian;

use cmf\lib\Plugin;

class TonglianPlugin extends Plugin {

    public $info = [
        'name'        => 'Tonglian', //Demo插件英文名，改成你的插件英文就行了
        'title'       => '同联授权登录',
        'description' => '同联授权登录',
        'status'      => 1,
        'author'      => 'DQ',
        'version'     => '0.1',
        'demo_url'    => '',
        'author_url'  => 'https://github.com/maoSting',
    ];


    public function install() {
        return true;
    }

    public function uninstall() {
        return true;
    }


}