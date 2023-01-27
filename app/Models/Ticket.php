<?php

namespace App\Models;

use App\Models\User;
use App\Models\Label;
use App\Models\Category;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'title',
        'description',
        'priority',
        'status',
        'customer_id',
        'agent_id',
    ];

    public const PRIORITY = ['high', 'medium', 'low'];
    public const STATUS = ['open', 'in progress', 'cancelled', 'completed'];

    public function userCustomers() {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    public function userAgent() {
        return $this->belongsTo(User::class, 'agent_id', 'id');
    }

    public function categories() {
        return $this->belongsToMany(Category::class);
    }

    public function labels() {
        return $this->belongsToMany(Label::class);
    }

    public function ticketlogs() {
        return $this->hasMany(TicketLog::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }
}
