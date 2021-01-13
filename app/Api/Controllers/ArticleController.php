<?php


namespace App\Api\Controllers;




use App\Api\Event\ArticleViewEvent;
use App\Api\Requests\ArticleRequest;
use App\Api\Resources\ArticleResource;
use App\Api\Services\ArticleService;
use App\Jobs\SaveKeyword;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class ArticleController extends Controller
{

    public function index(Request $request)
    {
        // 判断是否文章搜索
        if($request->has('q') && $request->get('q')){
//            var_dump(123);die;
//            var_dump($this->search($request));die;
            return $this->search($request);
        }

        $articles = Article::filter($request->all())
            ->latest()
            ->paginate($request->get('per_page',10));

        return ArticleResource::collection($articles); // json资源类
    }

    // 文章搜索
    public function search(Request $request)
    {
        $keyword = $request->get('q');

        $articles = Article::query()->where('title','like','%'.$keyword.'%')->paginate($request->get('per_page'));

        if ($keyword && $articles->total() > 0) {
            SaveKeyword::dispatchNow($keyword);  //记录日志事件交给调度器自动调度
        }
        return ArticleResource::collection($articles);
    }


    public function show($id)
    {
//        var_dump('this is show');die;
        $article = Article::filter()->find($id);  //找不到主键就直接抛错误
        if (!$article) {
            return '文章不存在';
        }
        event(new ArticleViewEvent($article)); //触发记录文章阅读次数

        $article->view_count = (new ArticleService())->getViewCount($article->id); //文章阅读次数


        return new ArticleResource($article);
    }


    /**
     * Notes: 发布文章
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @author: lujianjin
     * datetime: 2021/1/13 9:27
     */
    public function store(Request $request)
    {
        $article = new Article();

        $article->user_id = Auth::id()??1;
        $article->title = $request->input('title');
        $article->preview = $request->input('preview');
        $article->state = $request->input('state');
        $article->updated_at = now();
        $article->save();

        $article->tags()->sync($request->input('tags'));  // 通过提交的tags 生成文章与标签多对多关联的中间表

        return $this->withNoContent();
    }





}
