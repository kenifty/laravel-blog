<?php


namespace App\Api\Services;


use Illuminate\Support\Facades\Redis;

class ArticleService
{

    public $mdName = 'article_views';

    public $prefix = 'article_';

    public function getViewCount($id)
    {
        $table = $this->mdName;
        $prefix = $this->prefix.$id;
        return Redis::hGet($table,$prefix);
    }

}
