<?php

namespace App\Api\LIsteners;

use App\Api\Event\ArticleViewEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Redis;

class ArticleViewListener
{
    /**
     * 一个帖子的最大访问数
     */
    public $articleViewLimit = 2;

    /**
     * 同一用户浏览同一个帖子的过期时间
     */
    public $ipExpireSec = 200;

    /**
     * @var string 用于储存文章点击数的hash键
     */
    public $mdName = 'article_views';

    public $prefix = 'article_';

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ArticleViewEvent  $event
     * @return void
     */
    public function handle(ArticleViewEvent $event)
    {
        $field = $this->getHashField($event->article->id);
        $table = $this->getTable();

        $viewCount = Redis::hGet($table,$field);
//        var_dump($viewCount);die;
        if ($viewCount) {
            $viewCount++;
        } else {
            $viewCount = 1;
        }

        // 数据写入redis
        Redis::hSet($table,$field,$viewCount);
    }



    public function getTable()
    {
        return $this->mdName;
    }

    public function getHashField($id)
    {
        return $this->prefix.$id;
    }


}
