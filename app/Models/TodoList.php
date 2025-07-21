<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TodoList extends Model
{
    protected $table = "todo_lists";
    protected $fillable = [
        'user_id',
        'created_by',
        'todo_type',
        'next_payment_date',
        'deposit_date',
    ];
}
