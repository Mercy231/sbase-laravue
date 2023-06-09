<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static latest()
 * @method static create(array $fields)
 * @method static find($id)
 * @method static where(string $string, int $int)
 */
class Post extends Model
{
    use HasFactory;

    protected $table = "posts";
    protected $fillable = [
        "user_id",
        "title",
        "text",
    ];
    protected $with = [
        "user",
        "comments",
    ];
    public function user () : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function comments () : HasMany
    {
        return $this->hasMany(Comment::class)->latest();
    }
}
