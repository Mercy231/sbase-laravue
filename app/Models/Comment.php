<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    ];
    protected $with = [
        "user",
    ];
    public function user () : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function post () : BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
