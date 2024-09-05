<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'status', 'project_id', 'user_id','deadline','refusal_reason','drive_url'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function listItems()
    {
        return $this->hasMany(ListItem::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

}
