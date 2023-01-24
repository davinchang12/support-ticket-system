<?php

namespace App\Models;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function tickets() {
        return $this->belongsToMany(Ticket::class);
    }
}
