<?php
/**
 * Created by PhpStorm.
 * Author: DQ
 * Date: 2019/8/20
 * Time: 14:20
 */

namespace plugins\tonglian\model;
use think\Db;
use think\Model;

class UserModel extends Model{

    /**
     *
     * @param string $userName
     *                        用户名
     * @param int    $roleId
     *                      角色ID
     *
     * @return array|null|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * Author: DQ
     */
    public function register($userName = '', $roleId = 0){
        // 用户名
        $user['user_login'] = $userName;
        $user['user_email'] = sprintf('%s@mobile.com', $userName);
        $user['user_nickname'] = $userName;
        // 帐号类型
        $user['user_type'] = 1;
        $user['user_status'] = 1;
        // 密码
        $user['user_pass'] = cmf_password(uniqid());
        $user['last_login_time'] = time();
        $user['create_time'] = time();
        $user['last_login_ip'] = get_client_ip(0, true);
        $userId  = DB::name('user')->insertGetId($user);
        $data   = Db::name("user")->where('id', $userId)->find();
        Db::name('RoleUser')->insert(["role_id" => $roleId, "user_id" => $userId]);
        return $data;
    }
}