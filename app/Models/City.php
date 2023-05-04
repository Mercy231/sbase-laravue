<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static get(string[] $array)
 * @method static where(string $string, mixed $state)
 * @method static find(mixed $city)
 * @method static select(string $string)
 */
class City extends Model
{
    use HasFactory;

    protected $table = "cities";
}
