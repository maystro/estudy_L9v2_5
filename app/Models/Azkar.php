<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Azkar extends Model
{
    use HasFactory;
    protected $table='azkar';
    protected $primaryKey='id';
    protected $fillable=['idx','group_id','content','note','max_count'];

    public function group()
    {
        return $this->belongsTo(AzkarGroup::class);
    }

}
