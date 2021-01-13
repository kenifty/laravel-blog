<?php


namespace App\ModelFilters;


use EloquentFilter\ModelFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;


/**
 * 使用集合过滤包需要自定义过滤类
 * Class ArticleFilter
 * @package App\Api\ModelFilters
 * Created by lujianjin
 * DataTime: 2021/1/13 14:51
 */
class ArticleFilter extends ModelFilter
{
    public $relations = [];

    public function setup()
    {
        $this->onlyShowAllForFounder();
    }

    public function onlyShowAllForFounder()
    {
        if (Auth::user() && Auth::user()->hasRole('Founder')) {
            $this->withoutGlobalScopes();
        }
    }

    public function tagIds($ids)
    {
        $this->related('tags',function (Builder $builder) use ($ids) {
            $builder->whereIn('tags.id',(array)$ids);
        });
    }
}
