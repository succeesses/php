<?php

namespace app\index\controller;


use think\Request;
use think\exception\HttpResponseException;
use think\Response;
use think\controller;
use think\Db;

class API extends controller
{
    // 普通查询
    public function getinfro()
    {
        if (Request::instance()->isget()) {
            $result = Db::query('select * from users');
            $result_json = json_encode($result);
            return $result_json;
        }
    }

    public function setInfro()
    {
        if (Request::instance()->isPut()) {
            // 获取关键字
            $phone = Request::instance()->param('phone');
            echo $phone;
            $email = Request::instance()->param('email');
            echo $email;
            $password = Request::instance()->param('password');
            echo $password;
            if (empty($phone) && empty($email)) {
                $this->error('请至少修改一项');
            }
            $update = [];
            if ($phone) {
                $update['Phone'] = $phone;
            }
            if ($email) {
                $update['Email'] = $email;
            }
            $res = Db::table('users')->where('Password',$password)->update($update);
            if ($res) {
                $this->success('修改成功');
            } else {
                $this->error('修改失败');
            }
        } else {
            $this->error('请求失败, 请求需要为PUT方式');
        }
    }

}
