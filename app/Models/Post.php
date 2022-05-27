<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * App\Models\Post
 *
 * @property int $id
 * @property int $category_id
 * @property int $user_id
 * @property string $title
 * @property string|null $summary
 * @property string|null $seo_title
 * @property string|null $seo_keywords
 * @property string|null $seo_description
 * @property string|null $thumb 封面
 * @property string $content
 * @property string|null $original_url 原址
 * @property int $show 是否在列表展示
 * @property int $status
 * @property string|null $recommend_until 推荐截至时间
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post query()
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereOriginalUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereRecommendUntil($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereSeoDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereSeoKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereSeoTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereShow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereThumb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUserId($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tag[] $tags
 * @property-read int|null $tags_count
 * @property-read \App\Models\User|null $user
 */
class Post extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'updated_at'];

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::updated(function (Post $post) {
            if ($post->isDirty('user_id') && $original_user_id = $post->getOriginal('user_id')) {
                $originalUser = User::find($original_user_id);
                $originalUser->post_count = $originalUser->posts()->count();
                $post->user->post_count = $post->user->posts()->count();
                $originalUser->save();
                $post->user->save();
            }
        });

        static::created(function (Post $post) {
            $post->user->post_count = $post->user->posts()->count();
            $post->user->save();
        });


        static::deleted(function (Post $post) {
            $post->tags()->detach();
        });
    }
}
