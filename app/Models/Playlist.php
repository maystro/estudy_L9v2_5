<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Playlist extends Model
{
    use HasFactory;

    protected $table = 'playlists';
    protected $primaryKey = 'id';
    protected $fillable=['title','visible_to','user_role','idx','list_type'];

    const LIST_TYPE = [
        'audio'=>'صوت',
        'video'=>'فيديو',
        'book'=>'كتاب',
        'generic'=>'منوع',
        'MASTER_BOOKS'=>'كتب فضيلة الشيخ'
    ];

    public function lectures(): HasMany
    {
        return $this->hasMany(PlaylistFiles::class);
    }

    public function getVisibleTo()
    {
        return explode(',',$this->visible_to);
    }

    public function getUserRole()
    {
        return explode(',',$this->user_role);
    }


}
