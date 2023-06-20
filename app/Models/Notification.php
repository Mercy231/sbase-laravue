<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class Notification extends Model
{
    use HasFactory;

    protected $table = "notifications";

    protected $fillable = [
        "user_id",
        "title",
        "text",
        "status",
    ];
}
