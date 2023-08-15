<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ManufacturerDescriptionAdmin extends Model
{
    protected $connection= 'mysql2';
    protected $table = 'sd_manufacturer_description';
    protected $primaryKey = 'manufacturer_id';
    public $timestamps = false;
    protected $guarded = [];




}
