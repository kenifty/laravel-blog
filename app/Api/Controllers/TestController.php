<?php


namespace App\Api\Controllers;


use App\Models\Article;
use App\Models\ClientUser;
use App\Models\phone;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class TestController extends Controller
{

    public function index()
    {
//        $userInfo = DB::table('client_user')->select();
        $Md = new ClientUser;
        $Md->username = 'jianjin';
        $Md->save();

        $Phone = new phone();
        $Phone->phone = '13192607777';
        $Phone->client_user_id = '1';
        $Phone->save();
//        var_dump($res);die;
        $userInfo = ClientUser::query()->find(1)->phone;
        return $userInfo;
    }

    public function cache()
    {
//        Redis::set('name','jianjin');
        Redis::hSet('article:views','a_1','3');
    }
}
