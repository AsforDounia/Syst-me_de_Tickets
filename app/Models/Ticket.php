<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'category_id',
        'created_by',
        'assigned_to',
        'resolution_time',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // public function agent()
    // {
    //     return $this->belongsTo(User::class, 'assigned_to');
    // }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
   
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id'); // Assuming 'agent_id' as the foreign key
    }
}
