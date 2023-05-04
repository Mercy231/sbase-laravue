<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static get(string[] $array)
 * @method static where(string $string, mixed $country)
 * @method static find(mixed $state)
 * @method static select(string $string)
 */
class State extends Model
{
    use HasFactory;

    protected $table = "states";
}
