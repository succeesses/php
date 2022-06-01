<?php
namespace app\index\controller;
use think\Controller;
use think\Db;

class Index extends Controller
{
    // 进入登录页面
    public function index()
    {
        if (session('?userinfo')) {
            $this->success('您已经登录了，直接跳转到成功页！','is_login');
        }else{
            return view('index');
        }
    }

    // 验证登录
    public function login_check()
    {
        // 接收用户名和密码，并将各个数据两边空格去掉
        $username1 = trim(input('username1')); //email
        $username2 = trim(input('username2')); // 好像有点多余 // phone
        $password = md5(trim(input('password')));
        // 判断用户名是否存在
        $data = Db::name('users')->where('Email',$username1)->select();
        if (!$data) {
            $this->error('用户名不存在，请确认后重试！');
        }

        // 判断密码是否正确
        $data = Db::name('users')->where('Password',$password)->find();

        if ($data['Password'] == $password) {
            // 一般把用户信息存入session，记录登录状态
            $this->success('登录成功！','is_login');
        }else{
            $this->error('用户名和密码不匹配，请确认后重试！');
        }
    }

    public function signup()
    {

        return view('register');
    }

    public function signup_check()
    {
        // 接收用户名和密码，并将各个数据两边空格去掉
        $username1 = trim(input('username1')); // email
        $username2 = trim(input('username2')); // phone
        $password = trim(input('password')); // first password
        $password1 = trim(input('password1')); // second password

        if (strlen($username2) < 0 || strlen($username2) > 11) {
            $this->error('请输入合法的手机号');
        }
        // 两次密码必须相同
        if ($password != $password1) {
            $this->error('两次密码输入不相同！请重新输入密码');
        }
        // 判断用户名是否已经被注册
        $username_data = Db::name('users')->where('Email',$username1)->select();
        if ($username_data) {
            $this->error('该用户名已经存在，请换一个重试！');
        }
        $data = [
            'Email' => $username1,
            'Phone' => $username2,
            'Password' => md5($password)
        ];
        $status = Db::name('users')->insert($data);
        if ($status == 1) {
            $this->success('恭喜您注册成功，现在前往登录页！','Index');
            Session::set('USER_INFO_SESSION',$data);
        }else{
            $this->error('注册时出现问题，请重试！');
        }
    }
    // 登录成功后跳转页面
    public function is_login()
    {
        $info = session('userinfo');
        dump($info);
        echo '嗨，登录成功！' . '<br>';

    }

}
