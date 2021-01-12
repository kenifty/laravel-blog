<?php


namespace App\Api\Controllers;




use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{

    public function index(Request $request)
    {
        // 判断是否文章搜索
        if($request->has('q') && $request->get('q')){
            $this->search($request);
        }

        $articles = Article::filter($request->all())
            ->latest()
            ->paginate($request->get('per_page',10));

        return ArticleResource;
    }

    // 文章搜索
    public function search(Request $request)
    {

    }


}
