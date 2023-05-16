<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class Payment extends Model
{
    use HasFactory;

    protected $table = "payments";

    protected $fillable = [
        "user_id",
        "token_id",
        "card_number",
        "amount",
        "status",
    ];
}
