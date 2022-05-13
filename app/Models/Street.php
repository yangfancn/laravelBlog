<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Street
 *
 * @property int $code
 * @property int $province_code
 * @property int $city_code
 * @property int $area_code
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|Street newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Street newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Street query()
 * @method static \Illuminate\Database\Eloquent\Builder|Street whereAreaCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Street whereCityCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Street whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Street whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Street whereProvinceCode($value)
 * @mixin \Eloquent
 */
class Street extends Model
{
    use HasFactory;
}
