<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Visitor
 *
 * @property int $id
 * @property int $unique_view 唯一IP访问量
 * @property int $page_view 页面点击
 * @property int $baidu_spider
 * @property int $google_spider
 * @property int $360_spider
 * @property int $bing_spider
 * @property int $sougo_spider
 * @property int $soso_spider
 * @property int $byte_spider
 * @property int $other_spider
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor query()
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor where360Spider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereBaiduSpider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereBingSpider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereByteSpider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereGoogleSpider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereOtherSpider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor wherePageView($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereSosoSpider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereSougoSpider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereUniqueView($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visitor whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Visitor extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
}
