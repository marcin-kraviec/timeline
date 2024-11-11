<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'description',
        'image',
        'category_id',
        'created_by',
        'updated_by'
    ];

    public function parentUser() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function contributionUser() {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
