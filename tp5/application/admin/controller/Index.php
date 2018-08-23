<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\User as UserModel;
use think\Session;
use think\Db;
use think\File;
use think\Request;

class Index extends Controller
{
	public function index()
	{
		$name = Session::get('name','think');
		$uid = DB::name('user')->field('uid')->where("user_name='$name'")->find()['uid'];
		//$power = DB::name('power')->where("uid='$uid'")->field('power')->find()['power'];
		//$tu = strpos($power,'图片管理');
		
		//$nei = strpos($power,'内容管理');dump($power);die;
		//$admin = strpos($power,'全部');
		$this->assign('name',$name);
		$this->assign('uid',$uid);
		//$this->assign('tu',$tu);
		//$this->assign('nei',$nei);
		//$this->assign('admin',$admin);
		return $this->fetch();
	}

	public function welcome()
	{
		return $this->fetch();
	}

	public function member_list()
	{
		$user = DB::name('user')->select();
		$name = Session::get('name','think');
		$count = count($user);
		$this->assign('name',$name);
		$this->assign('user',$user);
		$this->assign('count',$count);
		return $this->fetch();
	}

	public function member_show()
	{
		$uid = Session::get('uid','think');
		
		$data = DB::name('user')->where("uid='$uid'")->find();
		$username = $data['user_name'];
		$pwd = $data['password'];
		$phone = $data['phone'];
		$uid = $data['uid'];
		$name = Session::get('name','think');
		$this->assign('name',$name);

		$this->assign('username',$username);
		$this->assign('phone',$phone);
		$this->assign('pwd',$pwd);
		$this->assign('uid',$uid);
		return $this->fetch();
	}

	public function member_msg()
	{
		$uid = $_GET['id'];
		//var_dump($uid);
		Session::set('uid',$uid,'think');
		if($uid) {
			echo json_encode(['state' => 1],true);
		} else {
			echo json_encode(['state' => 0],true);
		}
	}

	public function member_add()
	{
		$name = Session::get('name','think');
		$this->assign('name',$name);
		return $this->fetch();
	}

	public function member_inser()
	{

		$data = $_GET;
		//dump($data);
		if($data) {
				//$res = DB::name('user')->insert(['user_name' => $data['name'],'password' => $data['pwd'], 'phone' => $data['phone'],'porwer' => $data['bz']]);
				$res = DB::name('user')->insert(['user_name' => $data['username'],'password' => $data['password'], 'phone' => $data['mobile']]);
				if($res) {
	        	$status = 1;
	        	$message = '成功';
	        	return ['status' => $status, 'message' => $message];
	        } else {
	        	$status = 0;
	        	$message = '失败';
	        	return ['status' => $status, 'message' => $message];
	        }
		}
	}

	public function admin_del()
	{
		$id = $_GET['id'];
		$res = DB::name('user')->where("uid=$id")->delete();
		if($res) {
			$status = 1;
	        	$message = '成功';
	        	return ['status' => $status, 'message' => $message];
	        }else {
	        	$status = 0;
	        	$message = '失败';
	        	return ['status' => $status, 'message' => $message];
	        }
	}

	public function member_edit()
	{
		$uid = Session::get('uid','think');
		//var_dump($uid);
		
		$data = DB::name('user')->where("uid='$uid'")->find();
		$username = $data['user_name'];
		$phone = $data['phone'];
		$pwd = $data['password'];
		$name = Session::get('name','think');
		$this->assign('name',$name);

		$this->assign('username',$username);
		$this->assign('phone',$phone);
		$this->assign('pwd',$pwd);
		return $this->fetch();
	}

	public function admin_role()
	{
		$data = DB::name('role')->select();
		/*foreach ($data as &$value) {
			$uid = $value['uid'];
			$value['username'] = DB::name('user')->where("uid='$uid'")->field('user_name')->find()['user_name'];
		}*/
		$name = Session::get('name','think');
		$count = count($data);
		
		$this->assign('name',$name);
		$this->assign('data',$data);
		$this->assign('count',$count);

		return $this->fetch();
	}

	public function admin_permission()
	{
		$data = DB::name('power')->select();
		foreach ($data as $key => &$value) {
			$uid = $value['uid'];
			$value['name'] = DB::name('user')->where("uid='$uid'")->find()['user_name'];
			//dump($data);
		}
		$count = count($data);
		$name = Session::get('name','think');
		$this->assign('name',$name);
		$this->assign('data',$data);
		$this->assign('count',$count);

		return $this->fetch();
	}

	public function admin_permission_add()
	{
		$user = DB::name('user')->select();
		$name = Session::get('name','think');
		$this->assign('name',$name);
		$this->assign('user',$user);

		return $this->fetch();
	}

	public function permisson_inser()
	{
		$data = $_GET;
		//dump($data);
		if($data) {
				$res = DB::name('power')->insert(['uid' => $data['uid'],'power' => $data['name']]);
				if($res) {
	        	$status = 1;
	        	$message = '成功';
	        	return ['status' => $status, 'message' => $message];
	        } else {
	        	$status = 0;
	        	$message = '失败';
	        	return ['status' => $status, 'message' => $message];
	        }
		}
	}

	public function admin_add()
	{
		$name = Session::get('name','think');
		$this->assign('name',$name);

		return $this->fetch();
	}

	public function admin_role_add()
	{
		$data = DB::name('user')->select();

		$name = Session::get('name','think');
		$this->assign('name',$name);
		$this->assign('data',$data);

		return $this->fetch();
	}

	public function role_inser()
	{
		$data = $_GET;
		//dump($data);
		if($data) {
				//$res = DB::name('user')->insert(['user_name' => $data['name'],'password' => $data['pwd'], 'phone' => $data['phone'],'porwer' => $data['bz']]);
				$res = DB::name('role')->insert(['username' => $data['username'],'role_name' => $data['name'], 'role_detail' => $data['detail']]);
				if($res) {
	        	$status = 1;
	        	$message = '成功';
	        	return ['status' => $status, 'message' => $message];
	        } else {
	        	$status = 0;
	        	$message = '失败';
	        	return ['status' => $status, 'message' => $message];
	        }
		}
	}

	public function article_list()
	{
		$data = DB::name('xw')->select();
		//dump($data);
		$name = Session::get('name','think');
		$this->assign('name',$name);
		$this->assign('data',$data);

		return $this->fetch();
	}

	public function article_add()
	{
		$name = Session::get('name','think');
		$this->assign('name',$name);

		return $this->fetch();
	}

	public function article_add_update()
	{
		$data = input('post.');
		$type = $data['selected'];
		$title = $data['articletitle'];
		$content = $data['abstract'];
		$author = $data['author'];
		$time = date("Y-m-d H:i:s",strtotime('now'));
		$res = DB::name('xw')->insert(['title' => $title,'detail' => $content,'time' => $time,'author' => $author,'type' => $type]);

		if($res) {
			$this->redirect('admin/index/article_list');
		} else {
			$this->error('添加失败','admin/index/article_add');
		}
	}

	public function article_edit()
	{
		$id = $_GET['id'];
		$data = DB::name('xw')->where("id='$id'")->find();
		$type = $data['type'];
		$title = $data['title'];
		$detail = $data['detail'];
		$author = $data['author'];

		$name = Session::get('name','think');
		$this->assign('name',$name);
		$this->assign('type',$type);
		$this->assign('title',$title);
		$this->assign('detail',$detail);
		$this->assign('author',$author);
		$this->assign('id',$id);

		return $this->fetch();
	}

	public function article_edit_update()
	{
		$data = input('post.');
		$type = $data['selected'];
		$title = $data['articletitle'];
		$content = $data['abstract'];
		$author = $data['author'];
		$id = $data['id'];
		$time = date("Y-m-d H:i:s",strtotime('now'));
		$res = DB::name('xw')->where("id='$id'")->update(['title' => $title,'detail' => $content,'time' => $time,'author' => $author,'type' => $type]);

		if($res) {
			$this->redirect('admin/index/article_list');
		} else {
			$this->error('添加失败','admin/index/article_edit');
		}
	}

	public function article_detail()
	{
		$id = $_GET['id'];
		$data = DB::name('xw')->where("id=$id")->find();

		$name = Session::get('name','think');
		$this->assign('name',$name);
		$this->assign('data',$data);

		return $this->fetch();
	}

	public function about()
	{
		$data = DB::name('about')->find();
		$content = $data['content'];
		$centent_title = $data['centent_title'];
		$centent_content = $data['centent_content'];
		$confind = $data['confind'];
		$name = Session::get('name','think');


		$this->assign('content',$content);	
		$this->assign('centent_title',$centent_title);	
		$this->assign('centent_content',$centent_content);	
		$this->assign('confind',$confind);	
		$this->assign('name',$name);

		return $this->fetch();
	}

	public function about_edit()
	{
		$data = DB::name('about')->find();
		$content = $data['content'];
		$centent_title = $data['centent_title'];
		$centent_content = $data['centent_content'];
		$confind = $data['confind'];
		$id = $data['id'];
		$name = Session::get('name','think');

		$this->assign('content',$content);	
		$this->assign('centent_title',$centent_title);	
		$this->assign('centent_content',$centent_content);	
		$this->assign('name',$name);
		$this->assign('confind',$confind);
		$this->assign('id',$id);

		return $this->fetch();
	}

	public function about_update()
	{
		$getid = input('post.id');
		$new1 = input('post.content');
		$new2 = input('post.centent_title');
		$new3 = input('post.centent_content');
		$new = DB::name('about')->where("id='$getid'")->update(['content' => $new1, 'centent_title' => $new2, 'centent_content' => $new3]);
		if($new) {
			$this->success('编辑成功','admin/index/about');
		} else {
			$this->error('编辑失败','admin/index/about_edit');
		}
	}

	public function product_brand()
	{
		$name = Session::get('name','think');
		$this->assign('name',$name);

		return $this->fetch();
	}

	public function connet()
	{
		$phone =DB::name('connet')->find()['phone'];
		$qq =DB::name('connet')->find()['qq'];
		$wei =DB::name('connet')->find()['wei'];
		$name = Session::get('name','think');
		$this->assign('name',$name);
		$this->assign('phone',$phone);
		$this->assign('qq',$qq);
		$this->assign('wei',$wei);

		return $this->fetch();
	}

	public function connet_edit()
	{
		$id = $_GET['id'];
		$phone =DB::name('connet')->find()['phone'];
		$qq =DB::name('connet')->find()['qq'];
		$wei =DB::name('connet')->find()['wei'];
		$name = Session::get('name','think');
		$this->assign('name',$name);
		$this->assign('phone',$phone);
		$this->assign('qq',$qq);
		$this->assign('wei',$wei);
		$this->assign('id',$id);

		return $this->fetch();
	}

	public function connet_update()
	{
		$phone = input('post.mobile');
		$qq = input('post.qq');
		$wei = input('post.wei');
		if($phone) {
			$res = DB::name('connet')->where('id=1')->update(['phone' => $phone]);
		}
		if($qq) {
			$res = DB::name('connet')->where('id=1')->update(['qq' => $qq]);
		}
		if($wei) {
			$res = DB::name('connet')->where('id=1')->update(['wei' => $wei]);
		}
		
		if($res) {
			$this->redirect('admin/index/connet');
		} else {
			$this->redirect('admin/index/connet_edit');
		}
	}

	public function al()
	{
		$img = DB::name('al')->select();
		//dump($img);
		$count = count($img);
		$name = Session::get('name','think');
		$this->assign('name',$name);
		$this->assign('img',$img);
		$this->assign('count',$count);

		return $this->fetch();
	}

	public function al_get()
	{
		$file = request()->file('image');
		$title = $_POST['title'];
		if($file) {
			$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
			if ($info) {
                $img = $info->getSaveName();//获取名称
                $imgpath = DS.'uploads'.DS.$img;
                $path = str_replace(DS,"/",$imgpath);//数据库存储路径
                $res = DB::name('al')->insert(['img' => $path, 'img_title' => $title]);
                $al_id = DB::name('al')->getLastInsID();
                $result = DB::name('alxx')->insert(['al_id' => $al_id]);
                if($res) {
                	$status = 1;
                	$message = '图片上传成功';
                	return ['status' => $status, 'message' => $message];
                } else {
                	$status = 0;
                	$message = '图片上传失败';
                	return ['status' => $status, 'message' => $message];
                }
            } else {
                $status = 0;
                $message = '图片上传失败';
                return ['status' => $status, 'message' => $message];
            }
        }else{
            $status = 0;
            $message = '图片上传失败';
            return ['status' => $status, 'message' => $message];
		}
	}

	public function al_detile()
	{
		$id = $_GET['id'];
		$data = DB::name('alxx')->where("al_id=$id")->find();
		$img = DB::name('al')->where("id=$id")->find();
		$name = Session::get('name','think');
		$this->assign('name',$name);
		$this->assign('data',$data);
		$this->assign('img',$img);

		return $this->fetch();
	}

	public function al_edit()
	{
		$id = $_GET['id'];
		$data = DB::name('alxx')->where("al_id=$id")->find();
		$img = DB::name('al')->where("id=$id")->find();
		$name = Session::get('name','think');
		$this->assign('name',$name);
		$this->assign('data',$data);
		$this->assign('img',$img);

		return $this->fetch();
	}


	public function al_edit_update()
	{
		$file = request()->file('image');
		$title = $_POST['titles'];
		$id = $_POST['id'];
		$abstract = $_POST['abstract'];
		$client = $_POST['client'];
		$fuwu = $_POST['fuwu'];
		$class = $_POST['class'];
		$ping = $_POST['ping'];
		$result = DB::name('alxx')->where("al_id=$id")->update(['desc' => $abstract,'client' => $client,'class' => $class,'fuwu' => $fuwu,'ping' => $ping]);
		if($file) {
			$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
			if ($info) {
                $img = $info->getSaveName();//获取名称
                $imgpath = DS.'uploads'.DS.$img;
                $path = str_replace(DS,"/",$imgpath);//数据库存储路径
                $res = DB::name('al')->where("id=$id")->update(['img' => $path,'img_title' => $title]);
                
                if($res) {
                	$status = 1;
                	$message = '图片上传成功';
                	return ['status' => $status, 'message' => $message];
                } else {
                	$status = 0;
                	$message = '图片上传失败';
                	return ['status' => $status, 'message' => $message];
                }
            } else {
                $status = 0;
                $message = '图片上传失败';
                return ['status' => $status, 'message' => $message];
            }
        }else{
            $status = 0;
            $message = '图片上传失败';
            return ['status' => $status, 'message' => $message];
		}
	}

	public function al_del()
	{
		$id = $_GET['id'];
		$res = DB::name('al')->where("id=$id")->delete();
		//$result = DB::name('alxx')->where("al_id=$id")->delete();
	}
}