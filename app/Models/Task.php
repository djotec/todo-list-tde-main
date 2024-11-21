<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'doned',
        'order'
    ];
    protected static function booted()
    {
        static::creating(function ($task) {            
            $task->user_id = auth()->user()->id; 
        });
    }

    /**
     * Scope a query to only include popular users.
     */
    public function scopeFromUser(Builder $query): void
    {
        $query->where('user_id', auth()->user()->id);
    }
    



}