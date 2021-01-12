<?php


namespace App\Api\Resources;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * 统一资源返回处理类
 * Class Resource
 * @package App\Api\Resources
 * Created by lujianjin
 * DataTime: 2021/1/12 15:30
 */
class Resource extends JsonResource
{

    protected static $availableIncludes = [];

    private static $relationLoaded = false;

    public $preserveKeys = true;

    public function __construct($resource)
    {
        parent::__construct($resource);

        if (self::$relationLoaded && $resource instanceof Model) {
            $resource->loadMissing(self::getRequestIncludes());
        }
    }


    public static function getRequestIncludes()
    {
        $includes = array_intersect(parse_includes(static::$availableIncludes))
    }


}
