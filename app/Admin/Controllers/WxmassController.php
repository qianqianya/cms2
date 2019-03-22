<?php

namespace App\Admin\Controllers;

use App\Model\Wxmass;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use GuzzleHttp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class WxmassController extends Controller
{
    protected $redis_weixin_access_token = 'str:weixin_access_token';     //微信 access_token
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('Index')
            ->description('description')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Wxmass);

        $grid->id('Id');
        $grid->uid('Uid');
        $grid->openid('Openid');
        $grid->add_time('Add time');
        $grid->nickname('Nickname');
        $grid->sex('Sex');
        $grid->headimgurl('Headimgurl');
        $grid->subscribe_time('Subscribe time');
        $grid->unionid('Unionid');

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Wxmass::findOrFail($id));

        $show->id('Id');
        $show->uid('Uid');
        $show->openid('Openid');
        $show->add_time('Add time');
        $show->nickname('Nickname');
        $show->sex('Sex');
        $show->headimgurl('Headimgurl');
        $show->subscribe_time('Subscribe time');
        $show->unionid('Unionid');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Wxmass);

        $form->number('uid', 'Uid');
        $form->text('openid', 'Openid');
        $form->number('add_time', 'Add time');
        $form->text('nickname', 'Nickname');
        $form->switch('sex', 'Sex');
        $form->text('headimgurl', 'Headimgurl');
        $form->number('subscribe_time', 'Subscribe time');
        $form->text('unionid', 'Unionid');

        return $form;
    }
    /**
     * 获取微信AccessToken
     */
    public function getWXAccessToken()
    {

        //获取缓存
        $token = Redis::get($this->redis_weixin_access_token);
        if (!$token) {        // 无缓存 请求微信接口
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . env('WEIXIN_APPID') . '&secret=' . env('WEIXIN_APPSECRET');
            $data = json_decode(file_get_contents($url), true);

            //记录缓存
            $token = $data['access_token'];
            Redis::set($this->redis_weixin_access_token, $token);
            Redis::setTimeout($this->redis_weixin_access_token, 3600);
        }
        return $token;

    }
    /**
     * 获取用户信息
     * @param $openid
     */
    public function getUserInfo($openid)
    {
        //$openid = 'oo8Oz0skvOcYMVI-qHQb5gX43r0g';
        $access_token = $this->getWXAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $access_token . '&openid=' . $openid . '&lang=zh_CN';

        $data = json_decode(file_get_contents($url), true);
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        return $data;
    }
    /**
     * 消息群发
     */
    public function sendMsgView(Content $content)
    {
        //return view('admin.weixin.send_msg');

        return $content
            ->header('微信')
            ->description('群发消息')
            ->body(view('admin.mass.mass'));
    }

    /**
     *
     */
    public function sendMsg(Request $request)
    {
        $mass=$request->input('mass');
        $access_token = $this->getWXAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token='.$access_token;
        //var_dump($url);exit;
        $client = new GuzzleHttp\Client(['base_url' => $url]);
        $param = [
            "filter"=>[
                "is_to_all"=>true
            ],
            "text"=>[
                "content"=>$mass
            ],
            "msgtype"=>"text"
        ];
        ///var_dump($param);exit;
        $r = $client->Request('POST', $url, [
            'body' => json_encode($param, JSON_UNESCAPED_UNICODE)
        ]);
        //var_dump($r);exit;
        $response_arr = json_decode($r->getBody(), true);
        //echo '<pre>';
        //print_r($response_arr);
        // echo '</pre>';
        if ($response_arr['errcode'] == 0) {
            echo "发送成功";
        } else {
            echo "发送失败";
            echo '</br>';
            echo $response_arr['errmsg'];
        }
        //获取用户openid
        $list = Wxmass::all()->pluck('openid')->take(10)->toArray();


        //群发消息

        echo '<pre>';print_r($list);echo '</pre>';
        echo '<pre>';print_r($_POST);echo '</pre>';
    }

    /**
     * 菜单
     */
    public function tags(){
        //获取缓存
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . env('WEIXIN_APPID') . '&secret=' . env('WEIXIN_APPSECRET');
            $data = json_decode(file_get_contents($url), true);

            //记录缓存
            $token = $data['access_token'];
            Redis::set($this->redis_weixin_access_token, $token);
            Redis::setTimeout($this->redis_weixin_access_token, 3600);
        //var_dump($this->getWXAccessToken());die;
        // 1 获取access_token 拼接请求接口
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $token;

        //var_dump($url);die;
        //2 请求微信接口
        $client = new GuzzleHttp\Client(['base_url' => $url]);
        //var_dump($client);exit;
        $data = [
            "button" => [
                [
                    //"type"  => "view",      // view类型 跳转指定 URL
                    "name" => "网易云音乐",
                    "sub_button" => [
                        [
                            "type" => "view",
                            "name" => "搜索",
                            "url" => "https://www.soso.com/"
                        ],
                        [
                            "type" => "view",
                            "name" => "首页",
                            "url" => "https://music.163.com/"
                        ],
                        [
                            "type" => "click",
                            "name" => "当前时间",
                            "key" => "didi"
                        ]
                    ]
                ],
                [
                    "type" => "view",
                    "name" => "百度",
                    "url" => "https://www.baidu.com/"
                ],
                [
                    "type" => "view",
                    "name" => "欢乐欢乐",
                    "url" => "https://hlddz.qq.com/"
                ],

            ]

        ];
        //var_dump($data);exit;
        $r = $client->Request('POST', $url, [
            'body' => json_encode($data, JSON_UNESCAPED_UNICODE)
        ]);
        //var_dump($r);exit;

        // 3 解析微信接口返回信息

        $response_arr = json_decode($r->getBody(), true);
        //echo '<pre>';
        print_r($response_arr);
        //echo '</pre>';
        if ($response_arr['errcode'] == 0) {
            echo "菜单创建成功";
        } else {
            echo "菜单创建失败，请重试";
            echo '</br>';
            echo $response_arr['errmsg'];

        }
    }

}
