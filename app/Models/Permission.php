<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $table='permissions';
    protected $primaryKey='id';
    protected $guarded=[];
    protected $fillable=['user_id','permission'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }


}


