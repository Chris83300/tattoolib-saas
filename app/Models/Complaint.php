<?php

namespace App\Models;

use App\Enums\ComplaintStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_request_id',
        'user_id',
        'type',
        'description',
        'status',
        'admin_notes',
        'resolved_at',
    ];

    protected $casts = [
        'status' => ComplaintStatus::class,
        'resolved_at' => 'datetime',
    ];

    /**
     * Get the user who made the complaint.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the booking request.
     */
    public function bookingRequest()
    {
        return $this->belongsTo(BookingRequest::class);
    }
}
