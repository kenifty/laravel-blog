<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * Class Article
 * @package App\Models
 * Created by lujianjin
 * DataTime: 2021/1/12 10:21
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article filter($input = [], $filter = null)
 *
 *
 */
class Article extends BaseModel
{
    use HasFactory;
    use Filterable;
    protected $table = 'articles';
}
