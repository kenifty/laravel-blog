<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ClientUser
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $username
 * @property-read \App\Models\phone|null $phone
 * @method static \Illuminate\Database\Eloquent\Builder|ClientUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientUser whereUsername($value)
 * @mixin \Eloquent
 */
class ClientUser extends Model
{
//    use HasFactory;

    protected $table = 'client_user';

    public function phone()
    {
        return $this->hasOne('App\Models\phone','client_user_id');
    }
}
