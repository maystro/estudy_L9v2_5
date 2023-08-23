<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    use HasFactory;

    protected $table = 'user_details';
    protected $primaryKey = 'id';
    protected string $foreignKey = 'user_id';
    protected $fillable=['user_id','address','work_phone','home_phone','level_id','sub_level_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function levelId()
    {
        $rec = $this::where('user_id',$this->id)->first();
        return $rec->level_id;
    }

}
