<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static find($id)
 * @method static create(array $array)
 */
class Comment extends Model
{
    use HasFactory;

    protected $table = "comments";
    protected $fillable = [
        "post_id",
        "user_id",
        "text",
        "comment_id",
    ];
    protected $with = [
        "user",
        "comments",
    ];
    public function user () : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function post () : BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
    public function comments () : HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
