<?php


namespace App\Jobs;


use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;


/**
 * 记录检索关键字日志
 * Class SaveKeyword
 * @package App\Jobs
 * Created by lujianjin
 * DataTime: 2021/1/12 22:31
 */
class SaveKeyword
{
    use Dispatchable;  //任务调度器

    protected $keyword;

    public function __construct(string $keyword)
    {
        $this->keyword = $keyword;
    }

    public function handle()
    {
        try {
            $index = config('scout.elasticsearch.index');
            $host = config('scout.elasticsearch.hosts')[0];

            // elasticsearch中搜索关键字 返回token
            $tokens = Http::post(sprintf('%s/%s/_analyze',$host,$index),[
                'analyzer' => 'ik_smart',
                'text' => $this->keyword,
            ])['tokens'];

            $keywords = Arr::pluck($tokens,'token');  //检索到所有 'token'键的值

            $newKeywords = [];
            foreach ($keywords as $keyword) {
                if (strlen($keyword) >= 2) {
                    $newKeywords[] = $keyword;
                }
            }
            if ($newKeywords != $keywords) {
                $newKeywords[] = join('',$keywords);  // 关键字列表组合成一个字符串
            }

            // 记录关键字搜索日志到本地文件
            // 记录搜索事件 ip 关键字 和搜索用户
            $file = storage_path(sprintf('keywords/%s.log',now()->toDateString()));
            File::append($file,sprintf('[%s] %s "%s" %s'.PHP_EOL, now()->toDateTimeString(),request()->ip(),join('|',$newKeywords),Auth::id() ));

        } catch (Throwable $throwable) {
            Log::channel('single')->error('记录关键字日志失败:'.$throwable->getMessage());
        }
    }

}
