<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Village
 *
 * @property int $code
 * @property int $province_code
 * @property int $city_code
 * @property int $area_code
 * @property int $street_code
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|Village newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Village newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Village query()
 * @method static \Illuminate\Database\Eloquent\Builder|Village whereAreaCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Village whereCityCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Village whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Village whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Village whereProvinceCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Village whereStreetCode($value)
 * @mixin \Eloquent
 */
class Village extends Model
{
    use HasFactory;
}
