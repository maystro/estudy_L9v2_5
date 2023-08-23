<?php

namespace App\Models\Todo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoCategory extends Model
{
    use HasFactory;

    const Basic_Categories=[
        1=>'الصلاة',
        2=>'القرآن',
        3=>'الذكر',
        4=>'أعمال أخرى'
    ];

    protected $primaryKey ='id';
    protected $table='todos_categories';
    protected $fillable = ['category_title','category_idx'];

    static function todo_products($cat_id)
    {
        return TodoProduct::query()->where('todo_category_id',$cat_id)->get();
    }

}
