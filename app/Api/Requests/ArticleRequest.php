<?php


namespace App\Api\Requests;


use Illuminate\Foundation\Http\FormRequest;


/**
 * 文章提交表单数据验证层
 * Class ArticleRequest
 * @package App\Api\Requests
 * Created by lujianjin
 * DataTime: 2021/1/13 9:30
 */
class ArticleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required',
            'state' => 'required|in:0,1',
            'content.markdown' => 'required',
            'tags' => 'required|array',
        ];
    }

    public function attributes()
    {
        return [
           'title' => '标题',
           'state' => '状态',
           'content.markdown' => '内容',
           'tags' => '标签',
        ];
    }
}
