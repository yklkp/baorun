<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\User as UserModel;
use think\Url;
use think\Db;
use think\Session;

class User extends Controller
{
	public function check()
	{
		$data = input('post.');
		//dump($data);
		$name = $data['name'];
		Session::set('name',$name,'think');

		if($data) {
			$this->success("欢迎 {$name} 进入后台！",'admin/index/index');
		} else {
			$this->error('登陆失败！','index/index/login');
		}
	}

	public function check_name()
	{
		$name = $_GET['name'];
		//dump($name);die;
		$name_local = Db::name('user')->where("user_name='$name'")->find();
		if($name_local) {
			echo json_encode(['state' => 1],true);
		} else {
			echo json_encode(['state' => 0],true);
		}
	}

	public function check_pwd()
	{
		$name = $_GET['name'];
		$pwd = $_GET['pwd'];
		//dump($name);die;
		$pwd_local = Db::name('user')->where("user_name='$name'")->find();
		//var_dump($pwd_local['password']);
		if($pwd === $pwd_local['password']) {
			echo json_encode(['state' => 1],true);
		} else {
			echo json_encode(['state' => 0],true);
		}
	}

	public function check_yzm()
	{
		$yzm = $_GET['yzm'];
		if(!captcha_check($yzm)) {
			echo json_encode(['state' => 0],true);
		} else {
			echo json_encode(['state' => 1],true);
		}
	}

	public function upload()
	{
		$uid = $_GET['hide_id'];
		$file = $_GET['file'];
		$file = json_encode([$file =>0], true);
		$file = json_decode($file);
		dump($file);
		// 获取表单上传文件 例如上传了001.jpg
		//$file = request()->file('image');
		// 移动到框架应用根目录/public/uploads/ 目录下
		$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
		var_dump($info);
		if($info){
			// 成功上传后 获取上传信息
			// 输出 jpg
			echo $info->getExtension();
			// 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
			echo $info->getSaveName();
			// 输出 42a79759f284b767dfcb2a0197904287.jpg
			echo $info->getFilename();
			}else{
			// 上传失败获取错误信息
			echo $file->getError();
		}

		if($file) {
			echo json_encode(['state' => 1], true);
		} else {
			echo json_encode(['state' => 0], true);
		}
	}

	public function outlogin()
	{
		session('name',null);
        $this->redirect('index/index/login');
	}
		
}

