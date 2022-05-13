<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Site
 *
 * @property int $id
 * @property string $name
 * @property string $seo_title
 * @property string $seo_keywords
 * @property string $seo_description
 * @property string|null $icp
 * @property string|null $copyright
 * @property int $tc 简繁体转换
 * @method static \Illuminate\Database\Eloquent\Builder|Site newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Site newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Site query()
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereCopyright($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereIcp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereSeoDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereSeoKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereSeoTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereTc($value)
 * @mixin \Eloquent
 */
class Site extends Model
{
    use HasFactory;
}
