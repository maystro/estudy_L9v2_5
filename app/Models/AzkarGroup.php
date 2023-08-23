<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AzkarGroup extends Model
{
    use HasFactory;
    protected $table='azkar_groups';
    protected $primaryKey='id';
    protected $fillable=['title'];

    public function items()
    {
        return $this->hasMany(Azkar::class);
    }
    public function getItems($gid)
    {
        return Azkar::where('group_id',$gid)->get();
    }
}
