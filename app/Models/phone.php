<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\phone
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $phone
 * @property int $client_user_id
 * @method static \Illuminate\Database\Eloquent\Builder|phone newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|phone newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|phone query()
 * @method static \Illuminate\Database\Eloquent\Builder|phone whereClientUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|phone whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|phone whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|phone wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|phone whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class phone extends Model
{
    use HasFactory;

    protected $table = 'phone';
}
