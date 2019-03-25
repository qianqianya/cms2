<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\UserModel;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $u_email = $request->input('u_email');
        $u_pwd = $request->input('u_pwd');

        //$res = userModel::where(['u_email' => $u_email, 'u_pwd' => $u_pwd])->first();
        $data=[
            'u_email'=>$u_email,
            'u_pwd'=>$u_pwd,
        ];
        //echo json_encode($data);die;
        $url='http://passport.qianqianya.xyz/api/passport';
        $ch=curl_init($url);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        $res=curl_exec($ch);
        curl_close($ch);
        $res=json_encode($res,true);
        if($res){
            echo '登陆成功';
        }
    }
    public function reg(Request $request)
    {
        $u_name = $request->input('u_name');
        $u_email = $request->input('u_email');
        $u_pwd = $request->input('u_pwd');
        $u_tel = $request->input('u_tel');


        $res = UserModel::insert(['u_email' => $u_email, 'u_pwd' => md5($u_pwd), 'u_tel' => $u_tel, 'u_name' => $u_name]);
        if ($res) {
            return json_encode(
            [
            'status' => 1000,
            'msg' => '注册成功'
            ]
            );
        } else {
            return json_encode(
            [
            'status' => 1,
            'msg' => '注册失败'
            ]
            );

        }
    }
    public function token()
    {
        $data=[
            'u_id'=>$_POST['u_id'],
            'token'=>$_POST['token']
        ];
        $url="http://passport.qianqianya.xyz/api/token";
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,true);//文件上传
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data); //文件上传
        curl_setopt($ch,CURLOPT_HEADER,0);//不返回头部信息
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//
        //抓取url传给浏览器
        $res=curl_exec($ch);
        return $res;
    }

    public function quit()
    {
        $data=[
            'u_id'=>$_POST['u_id']
        ];
        $url="http://passport.qianqianya.xyz/api/quit";
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,true);//文件上传
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data); //文件上传
        curl_setopt($ch,CURLOPT_HEADER,0);//不返回头部信息
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//
        //抓取url传给浏览器
        $res=curl_exec($ch);
        return $res;
    }

}
