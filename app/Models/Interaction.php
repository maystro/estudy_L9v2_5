<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interaction extends Model
{
    use HasFactory;
    protected $table='interactions';
    protected $primaryKey='id';
    protected $fillable=['user_id','target_id','target_table','action'];

    const ACTIONS_LIST =[
        'visit'=>'visit',
        'download'=>'download',
        'like'=>'like',
        'dislike'=>'dislike',
        'favorite'=>'favorite'
    ];

    static function find_visits_by_current_user($target_id,$target_table)
    {
        $result = Interaction::where('target_id',$target_id)
            ->where('target_table',$target_table)
            ->where('user_id',auth()->user()->id)
            ->where('action',Interaction::ACTIONS_LIST['visit']);
        return $result;
    }

    static function add_visit_for_current_user($target_id,$target_table)
    {
        $user_id = auth()->user()->id;
        $result = Interaction::create([
            'user_id'=>$user_id,
            'target_id'=>$target_id,
            'target_table'=>$target_table,
            'action'=> Interaction::ACTIONS_LIST['visit']
        ]);
        return $result;
    }

    static function get_visits($target_id,$target_table){
        $result = Interaction::where('target_id',$target_id)
            ->where('target_table',$target_table)
            ->where('action',Interaction::ACTIONS_LIST['visit']);
        return $result;
    }
}
