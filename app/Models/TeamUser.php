<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamUser extends Model
{
    use HasFactory;
    protected $primaryKey='id';
    protected $table='team_user';
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'role',
        'team_id',
    ];

    public function user()
    {
        return User::query()->find($this->user_id);
    }
}
