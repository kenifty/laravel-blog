<?php


namespace App\Api\Resources;


use App\Api\Utils\Html2wxml\ToWXML;
use App\Models\Article;

class ContentResource extends Resource
{
    public function toArray($request)
    {
        $data = parent::toArray($request);

        if ($request->has('htmltowxml') && $request->header('x-Client') == 'wechat') {
            do {
                if (
                    $this->resource->contentable_type == Article::class &&
                    !$request->routeIs('articles.show')
                ) {
                    $data['htmltowxml'] = [];
                    break;
                }

                // 接收上传的markdown文档解析
                $body = Parsedown::instance()->setBreaksEnabled(true)->text($this->resource->combine_markdown);

                $data['htmltoxml'] = app(ToWXML::class)->towxml($body,[
                    'type' => 'html',
                    'highlight' => true,
                    'linenums' => $request->has('htmltoxml_linenums'),
                    'imghost' => null,
                    'encode' => false,
                    'highlight_languages' => [
                        'php',
                        'javascript',
                        'typescript',
                        'java',
                        'css',
                        'less',
                        'bash',
                        'ini',
                        'json',
                        'sql',
                    ],
                ]);
            } while(false);
        }
        return $data;
    }
}
