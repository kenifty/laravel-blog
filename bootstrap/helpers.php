<?php


if (!function_exists('friendly_numbers')) {
    function friendly_numbers($n,$p = 1)
    {
        $v = pow(10,$p);

        if ($n > 1000) {
            return intval($n * $v/1000) / $v .'k';
        }

        return (string)$n;
    }
}

if (!function_exists('is_online')) {
    function is_online($user)
    {
        $id = $user instanceof \App\Models\User ? $user->id : $user;

        try {
            // 通过laravel echo服务里面获取用户信息 判断用户是否在线
            // 代码处于无法访问$app变量的位置，使用全局辅助函数获取类的实例
            $response = resolve(\GuzzleHttp\Client::class)->get(
                sprintf(
                  '%s/apps/%s/channels/%s',
                  config('app.laravel_echo_server_url'), // 获取laravel echo 配置
                  config('app.laravel_echo_server_app_id'),
                    new \Illuminate\Broadcasting\PrivateChannel('App.Models.User.'.$id) // 广播私有频道
                ),
                [
                   'query' => ['auth_key'=>config('app.laravel_echo_server_key')],
                   'timeout' => 3,
                ]
            );

            $result = json_decode($response->getBody()->getContents());

            return $result->occupied;
        } catch (Exception $exception) {
            return false;
        }
    }
}
