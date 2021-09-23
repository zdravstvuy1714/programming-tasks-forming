<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';

    protected $fillable = [
        'title',
        'content',
        'category_id',
    ];

    protected $casts = [
        'title'         => 'string',
        'content'       => 'string',
        'category_id'   => 'integer',
    ];
}
