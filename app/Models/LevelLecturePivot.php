<?php

namespace App\Models;

use App\Http\Livewire\Admin\Levels\LevelsTable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class LevelLecturePivot extends Model
{
    use HasFactory;
    protected $table='levels_lectures';
    protected $fillable=['lecture_id','level_id'];

    public function levels(): Collection
    {
        $level_ids = LevelLecturePivot::query()
            ->where('lecture_id',$this->lecture_id)
            ->get()
            ->pluck('level_id')
            ->toArray();

        return Level::query()->whereIn('id',$level_ids)
            ->get()->pluck(['id','title']);
    }
}
