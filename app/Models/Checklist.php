<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    protected $guarded = [];

    protected $table = 'checklist';

    protected $fillable = ['title'];

    public function items()
    {
        return $this->hasMany(ItemList::class, 'checklist_id');
    }
}
