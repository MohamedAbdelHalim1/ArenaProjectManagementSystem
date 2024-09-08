<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListItem extends Model
{
    use HasFactory;

    protected $fillable = ['task_id', 'text', 'is_checked','parent_id','staff_id'];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function children()
    {
        return $this->hasMany(ListItem::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(ListItem::class, 'parent_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
