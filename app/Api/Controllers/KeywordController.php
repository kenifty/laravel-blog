<?php


namespace App\Api\Controllers;


use App\Api\Resources\Resource;
use Illuminate\Support\Facades\Cache;

class KeywordController extends Controller
{
    public function hot()
    {
        $keywords = collect(Cache::get('hot_keywords',[]))
            ->sortByDesc('count')
            ->take(5)
            ->pluck('keyword');

        return new Resource($keywords);

    }
}
