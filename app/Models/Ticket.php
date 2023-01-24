<?php

namespace App\Models;

use App\Models\User;
use App\Models\Label;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'title',
        'description',
        'priority',
        'status',
        'user_id',
    ];

    public function users() {
        return $this->belongsTo(User::class);
    }

    public function categories() {
        return $this->belongsToMany(Category::class);
    }

    public function labels() {
        return $this->belongsToMany(Label::class);
    }
}
