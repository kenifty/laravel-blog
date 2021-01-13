<?php

/**
 * 简化好友数显示格式 千位置换成k
 */
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


/**
 * 判断用户是否在线
 */
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
            \Illuminate\Support\Facades\Log::channel('single')->error('用户在线状态获取失败！');
            return false;
        }
    }
}


/**
 * 解析请求中的include参数 表示关联关系 以,分隔 转化成数组
 */
if (!function_exists('parse_includes')) {
    function parse_includes($includes = null)
    {
        if (is_null($includes)){
            $includes = request('include');
        }

        if (!is_array($includes)) {
            $includes = array_filter(explode(',',$includes));
        }

        $parsed = [];
        foreach ($includes as $include) {
            $nested = explode('.',$include);

            $part = array_shift($nested);
            $parsed[] = $part;

            while (count($nested) > 0) {
                $part .= '.' .array_shift($nested);
                $parsed = $part;
            }
        }

        return array_values(array_unique($parsed));
    }
}
