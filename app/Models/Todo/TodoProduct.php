<?php

namespace App\Models\Todo;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoProduct extends Model
{
    use HasFactory;
    protected $primaryKey ='id';
    protected $table='todos_products';
    protected $fillable = ['product_title','product_idx','todo_id','todo_category_id'];

    static function getTodoItems($product_id)
    {
        return TodoItem::query()->where('product_id',$product_id)
            ->get();
    }
    public function items()
    {
        return $this->hasMany(TodoItem::class,'product_id');
    }
}
