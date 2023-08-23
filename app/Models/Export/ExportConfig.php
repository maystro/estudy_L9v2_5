<?php

namespace App\Models\Export;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportConfig extends Model
{
    use HasFactory;
    protected $table='export_config';
    protected $fillable=['header','footer','column_labels'];
    public $timestamps=false; //to ignore timestamp fields
}
