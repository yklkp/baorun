<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use app\index\model\Person as PersonModel;

class Index extends Controller
{
    public function index()
    {
        $data = DB::name('about')->find();
        $confind = $data['confind'];
        $phone = DB::name('connet')->find()['phone'];
        $qq = DB::name('connet')->find()['qq'];
        $wei = DB::name('connet')->find()['wei'];
        $this->assign('phone',$phone);
        $this->assign('qq',$qq);
        $this->assign('wei',$wei);

        $this->assign('confind',$confind);
        return $this->fetch();
    }

    public function login()
    {
    	return $this->fetch();
    }

    public function al()
    {
        $data = DB::name('al')->select();

        $this->assign('data',$data);
    	return $this->fetch();
    }

    public function alxx()
    {
    	return $this->fetch();
    }

    public function xw()
    {
        $data = DB::name('xw')->select();
        //dump($data);

        //添加日期和月份分开的数组
        foreach ($data as &$value) {
            $time = explode("-", explode(" ", $value['time'])[0]);
            $value['day'] = $time[2];
            $value['m'] = $time[0].'/'.$time[1];
        }
        
        $this->assign('data',$data);
    	return $this->fetch();
    }

    public function xwxx()
    {
        $id = $_GET['id'];
        $data = DB::name('xw')->where("id=$id")->find();

        $this->assign('data',$data);
    	return $this->fetch();
    }

    public function ab()
    {
        $data = DB::name('about')->find();
        $content = $data['content'];
        $centent_title = $data['centent_title'];
        $centent_content = $data['centent_content'];

        $this->assign('content',$content);  
        $this->assign('centent_title',$centent_title);  
        $this->assign('centent_content',$centent_content);
    	return $this->fetch();
    }

    public function lx()
    {
    	$phone =DB::name('connet')->find()['phone'];
        $qq =DB::name('connet')->find()['qq'];
        $wei =DB::name('connet')->find()['wei'];
        $this->assign('phone',$phone);
        $this->assign('qq',$qq);
        $this->assign('wei',$wei);

        return $this->fetch();
    }
}
