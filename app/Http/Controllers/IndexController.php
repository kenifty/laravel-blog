<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $id = Auth::id();
//        var_dump($user);
        var_dump($id);
//        var_dump($request->user());
//        die;
//        echo $user;
        return 'this is http index'.$user;
    }

    public function test()
    {
        var_dump(app()->getRoutes());die;
        var_dump(session_status() === PHP_SESSION_ACTIVE);
        return 'this is test';
    }
}
