<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Boolean;

class PlaylistFiles extends Model
{
    use HasFactory;

    protected $table='playlistfiles';
    protected $primaryKey='id';
    protected $fillable=
    [
        'playlist_id',
        'lecture_id',
        'idx',
    ];

    public function playlist()
    {
        return $this->belongsTo(Playlist::class);
    }

    public function addLike() :bool
    {
        $user_id = auth()->user()->id;
        if (! $this->hasLike())
        {
            Interaction::UpdateOrCreate([
                'user_id'=>$user_id,
                'target_id'=>$this->id,
                'target_table'=> $this->table,
                'action'=> Interaction::ACTIONS_LIST['like']
            ]);
            return true;
        }
        else
            return false;
    }

    public function likeCount() :int
    {
        $result = Interaction::query()->where([
            ['target_id','=',$this->id],
            ['target_table','=',$this->table],
            ['action','=','like']
        ]);
        return $result->count();
    }
    public function hasLike() : bool
    {
        $result = Interaction::query()->where([
            ['target_id','=',$this->id],
            ['target_table','=',$this->table],
            ['action','=','like'],
            ['user_id','=',auth()->user()->id]
        ]);
        return $result->count() > 0;
    }

}
