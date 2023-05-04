<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static get(string[] $array)
 * @method static find(mixed $country)
 * @method static select(string $string)
 */
class Country extends Model
{
    use HasFactory;

    protected $table = "countries";
}
