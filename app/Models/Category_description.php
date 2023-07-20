<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category_description extends Model
{
    protected $connection='mysql2';
    protected $table='sd_category_description';
    use HasFactory;
}
