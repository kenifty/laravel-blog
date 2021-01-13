<?php

namespace App\Models;

use App\Api\Services\ArticleService;
use App\Models\Traits\WithDiffForHumanTimes;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Laravel\Scout\Searchable;
use Overtrue\LaravelFavorite\Traits\Favoriteable;
use Overtrue\LaravelFollow\Followable;
use Overtrue\LaravelLike\Traits\Likeable;


/**
 * Class Article
 *
 * @package App\Models
 * Created by lujianjin
 * DataTime: 2021/1/12 10:21
 * @property int $id
 * @property int $user_id
 * @property int $state
 * @property string $title
 * @property string $preview
 * @property int $heat 热度
 * @property array|null $cache 数据缓存
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article filter($input = [], $filter = null)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tag[] $tags
 * @property-read int|null $tags_count
 * @method static \Illuminate\Database\Eloquent\Builder|Article newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Article query()
 * @method static \Illuminate\Database\Eloquent\Builder|Article simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCache($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereHeat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Article wherePreview($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereUserId($value)
 * @mixin \Eloquent
 */
class Article extends BaseModel
{
    use HasFactory;
    // 使用eloquentfilter包 对ELoquent进行条件查询
    use Filterable;
    use WithDiffForHumanTimes;
    use Followable; // 用户关注包
    use Favoriteable; // 用户喜爱包
    use Likeable; // 用户喜欢包
//    use Searchable;  // 使用Searchable做ELoquent的全文搜索驱动

    protected $table = 'articles';

    protected $with = ['content'];

    protected $fillable = [
        'user_id',
        'state',
        'title',
        'preview',
        'view_count',
        'cache',
        'cache->favorites_count',
        'cache->likes_count',
        'cache->comments_count',
    ];

    const HEAT_VIEWS = 10;

    const HEAT_LIKE = 100;

    const HEAT_COMMENT = 500;

    const HEAT_FAVORITE = 1000;

    const CACHE_FIELDS = [
        'view_count'       => 0,
        'favourites_count' => 0,
        'likes_count'      => 0,
        'comments_count'   => 0
    ];

    protected $casts = [
      'id' => 'int',
      'user_id' => 'int',
      'cache' => 'json',
    ];

//    protected $appends = [
//        'created_at_timeago',
//        'updated_at_timeago',
//        'friendly_views_count',
//        'friendly_comments_count',
//        'friendly_likes_count',
//    ];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('visible', function (Builder $builder) {
            $builder->where('state', 1);
        });
    }


    public function setCacheAttribute($value)
    {
        $value = is_array($value) ? $value : json_decode(
            (string)array_merge($this->cache, Arr::only($value, array_keys(self::CACHE_FIELDS)))
        );
    }

    public function getCacheAttribute($value)
    {
        return array_merge(self::CACHE_FIELDS, json_decode($value ?? '{}',true));
    }

    public function getFriendlyViewsCountAttribute()
    {
        return friendly_numbers($this->cache['views_count']);
    }

    public function getFriendlyCommentsCountAttribute()
    {
        return friendly_numbers($this->cache['comments_count']);
    }

    public function getFriendlyLikesCountAttribute()
    {
        return friendly_numbers($this->cache['likes_count']);
    }

    public function getHasFavoritedAttribute()
    {
        if (Auth::guest()) {
            return false;
        }

        return $this->relationLoaded('favoriters')
            ? $this->favoriters->contains(Auth::user())
            : $this->isFavoritedBy(Auth::id());

    }

    public function getHasLikedAttribute()
    {
        if (Auth::guest()) {
            return false;
        }

        return $this->relationLoaded('likers')
            ? $this->likers->contains(Auth::user())
            : $this->isLikedBy(Auth::id());
    }

    public function getUrlAttribute()
    {
        return sprintf('%s/articles/%d', config('app.site_url'), $this->id);
    }

    public function content()
    {
        return $this->morphOne(Content::class, 'contentable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }


    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');  // 标签与文章是多对多关系
    }

    // 定义索引里面的type
    public function searchableAs()
    {
        return 'articles';
    }

    // 定义有哪些字段需要搜索
    public function toSearchableArray()
    {
        return [
            'title' => $this->title,
            'content' => $this->content ? $this->content->markdown : '',
        ];
    }

    public function shouldBeSearchable()
    {
        return $this->state == 1; // visible 字段和 model visible 属性有冲突...
    }

    public function refreshCache()
    {
        $this->update([
            'cache' => array_merge($this->cache, [
                'favorites_count' => $this->favoriters()->count(),
                'likes_count' => $this->likers()->count(),
                'comments_count' => $this->comments()->count(),
            ]),
        ]);
    }

}
