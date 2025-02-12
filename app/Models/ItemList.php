<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemList extends Model
{
    protected $guarded = [];

    protected $table = 'items_list';

    protected $fillable = ['checklist_id', 'content', 'is_done'];
}
