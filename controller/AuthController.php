<?php

namespace plugins\tonglian\controller;

use cmf\controller\PluginBaseController;
use Curl\Curl;
use plugins\tonglian\model\UserModel;
use think\Db;

class AuthController extends PluginBaseController {

    public function index() {
        $token = $this->request->param('token');
        if (empty($token)) {
            $this->error('token 不能为空');

            return false;
        }

        $config = $this->getPlugin()->getConfig();
        if (empty($config['user_info_url'])) {
            $this->error('未配置获取用户信息url');

            return false;
        }
        $url = $config['user_info_url'];
        if (empty($config['user_role'])) {
            $this->error('未配置获取用户信息url');

            return false;
        }
        $role   = $config['user_role'];
        $prefix = $config['user_prefix'];

        $html = $this->requestUserInfo($url, $token);
        if ($html['status'] == false) {
            $this->error($html['msg']);

            return false;
        }

        $json = json_decode($html['data'], true);
        $data = $json['data'];
        // 判断管理员，是否注册
        $userName = $prefix . $data['phone'];

        $exist = Db::name('user')->where('user_login', $userName)->where('user_status', 1)->where('user_type', 1)->find();
        if (empty($exist)) {
            // 注册用户
            $userM = new UserModel();
            $exist = $userM->register($userName, $role);
        }
        /**
         * 写session
         */
        //登入成功页面跳转
        session('ADMIN_ID', $exist["id"]);
        session('name', $exist["user_login"]);
        $result['id']              = $exist['id'];
        $result['last_login_ip']   = get_client_ip(0, true);
        $result['last_login_time'] = time();
        $token                     = cmf_generate_user_token($exist["id"], 'web');
        if (!empty($token)) {
            session('token', $token);
        }
        Db::name('user')->update($result);
        cookie("admin_username", $userName, 3600 * 24 * 30);
        session("__LOGIN_BY_CMF_ADMIN_PW__", null);
        $this->success(lang('LOGIN_SUCCESS'), url("admin/Index/index"));

        return true;
    }

    /**
     * 通过url获取用户信息
     *
     * @param $url
     *
     * @param $token
     *
     * @return array
     *              status bool
     *              msg string
     *              data    string
     * @throws \ErrorException
     * Author: DQ
     */
    protected function requestUserInfo($url = '', $token = '') {
        $data = [
            'status' => false,
            'msg'    => '',
            'data'   => ''
        ];
        $curl = new Curl();
        $curl->setTimeout(3);
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $curl->setOpt(CURLOPT_SSL_VERIFYHOST, false);
        //        $curl->setHeader("Content-Type", "application/json");
        $curl->get($url, ['AuthToken' => $token]);
        if ($curl->httpStatusCode != 200) {
            $data['msg'] = '通过url，无法获取用户信息';

            return $data;
        }
        $json = json_decode($curl->getRawResponse(), true);
        if ($json['code'] != 200) {
            $data['msg'] = '用户信息获取失败';

            return $data;
        }
        $data['status'] = true;
        $data['data']   = $curl->getRawResponse();

        return $data;
    }
}