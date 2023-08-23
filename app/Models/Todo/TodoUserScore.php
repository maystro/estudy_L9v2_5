<?php

namespace App\Models\Todo;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoUserScore extends Model
{
    use HasFactory;
    protected $primaryKey ='id';
    protected $table='todos_users_scores';
    protected $fillable = [
        'score',
        'note',
        'checked',
        'user_id',
        'todo_item_id',
    ];

    public function todoItem()
    {
        return $this->hasOne(TodoItem::class);
    }

}
