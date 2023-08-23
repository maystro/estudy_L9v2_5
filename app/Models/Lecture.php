<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class Lecture extends Model
{
    use HasFactory;

    protected $table = 'lectures';
    protected $primaryKey = 'id';
    protected $fillable=[
        'subject_id','level_id','lecture_number',
        'file_order','title','original_filename',
        'filename','folder','active',
        'visit','download'];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function getLevels(): Collection
    {
        $level_ids = LevelLecturePivot::query()
            ->where('lecture_id',$this->id)
            ->get()
            ->pluck('level_id')
            ->toArray();

        return Level::query()->whereIn('id',$level_ids)
            ->get()->pluck(['id','title']);
    }

    static function levels($lecture_id): Collection
    {
        return LevelLecturePivot::query()
            ->join('lectures','levels_lectures.lecture_id','=','lectures.id')
            ->join('levels','levels_lectures.level_id','=','levels.id')
            ->where('lecture_id',$lecture_id)->get()->pluck('title','id');
    }

    public function playlists()
    {
        return PlaylistFiles::query()->where('lecture_id','=',$this->id)->get();
    }

    public function files()
    {
        return $this::where('lecture_number',$this->lecture_number)->get();
    }

    static function addLike($lecture_id)
    {
        $user_id = auth()->user()->id;
        $result = Interaction::UpdateOrCreate([
            'user_id'=>$user_id,
            'target_id'=>$lecture_id,
            'target_table'=>'lectures',
            'action'=> Interaction::ACTIONS_LIST['like']
        ]);
        return $result;
    }
    static function removeLike($lecture_id)
    {
        $user_id = auth()->user()->id;
        $record = Interaction::query()
            ->where('target_id','=',$lecture_id)
            ->where('user_id','=',$user_id)
            ->where('target_table','=','lectures');
        $record->delete();
    }

    static function hasLike($lecture_id) : bool
    {
        $user_id = auth()->user()->id;
        $record = Interaction::query()
            ->where('target_id','=',$lecture_id)
            ->where('user_id','=',$user_id)
            ->where('target_table','=','lectures')
            ->first();
        return (bool)$record;
    }
    static function likesCount($lecture_id) : int
    {
        $record = Interaction::query()
            ->where('target_id','=',$lecture_id)
            ->where('target_table','=','lectures')
            ->where('action','=','like')->get();

        return $record->count();
    }
    public function addVisit()
    {
        $user_id = auth()->user()->id;
        $result = Interaction::UpdateOrCreate([
            'user_id'=>$user_id,
            'target_id'=>$this->id,
            'target_table'=>'lectures',
            'action'=> Interaction::ACTIONS_LIST['visit']
        ]);
        return $result;
    }
    public function visits()
    {
//        $lec = Lecture::query()->find($this->id);
//        return $lec->visit;
        $record = Interaction::query()
            ->where('target_id','=',$this->id)
            ->where('target_table','=','lectures')
            ->where('action','=','visit')->get();

        return $record->count();

    }


}
