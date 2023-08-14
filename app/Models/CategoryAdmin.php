<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class CategoryAdmin extends Model
{
    protected $connection= 'mysql2';
    protected $table = 'sd_category';
    protected $primaryKey = 'category_id';
    public $timestamps = false;
    protected $guarded = [];







}
