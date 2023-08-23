<?php

namespace App\Models\Todo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoItem extends Model
{
    use HasFactory;
    protected $primaryKey ='id';
    protected $table='todos_items';
    protected $fillable = [
        'product_id',
        'item_title',
        'item_idx',
        'description',
        'max_score',
        'item_type'
    ];

}
