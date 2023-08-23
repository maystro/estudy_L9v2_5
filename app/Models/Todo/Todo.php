<?php

namespace App\Models\Todo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;
    protected $primaryKey ='id';
    protected $table='todos';
    protected $fillable = ['title','idx'];

    public function products()
    {
        return $this->hasMany(TodoProduct::class,'todo_id')->get();
    }
    public function items()
    {
        $items = $this->hasManyThrough(
            TodoItem::class,TodoProduct::class,
            'todo_id' ,'product_id',
            'id','id')->get();
        return $items;
    }

}

