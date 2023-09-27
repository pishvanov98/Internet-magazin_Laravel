<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Manufacturer extends Model
{
    protected $connection= 'mysql2';
    protected $table = 'sd_manufacturer';
    protected $primaryKey = 'manufacturer_id';
    public $timestamps = false;
    protected $guarded = [];




}
