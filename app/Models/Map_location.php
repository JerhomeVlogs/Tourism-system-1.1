<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Map_location extends Model
{
    protected $fillable = ['visit_count'];
    use HasFactory;
}
