<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Enquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'ip_address',
        'user_agent',
        'is_spam',
        'read_at',
    ];

    protected $casts = [
        'is_spam' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Scope to filter unread enquiries
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope to filter read enquiries
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Scope to filter spam enquiries
     */
    public function scopeSpam($query)
    {
        return $query->where('is_spam', true);
    }

    /**
     * Scope to filter non-spam enquiries
     */
    public function scopeNotSpam($query)
    {
        return $query->where('is_spam', false);
    }

    /**
     * Scope to search enquiries by email or subject
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('email', 'like', "%{$search}%")
              ->orWhere('subject', 'like', "%{$search}%")
              ->orWhere('name', 'like', "%{$search}%");
        });
    }

    /**
     * Scope to filter by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Mark enquiry as read
     */
    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }

    /**
     * Mark enquiry as spam
     */
    public function markAsSpam()
    {
        $this->update(['is_spam' => true]);
    }

    /**
     * Mark enquiry as not spam
     */
    public function markAsNotSpam()
    {
        $this->update(['is_spam' => false]);
    }

    /**
     * Get the formatted created date
     */
    protected function formattedCreatedAt(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->created_at->format('M d, Y \a\t g:i A'),
        );
    }

    /**
     * Get the status of the enquiry
     */
    protected function status(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->is_spam) {
                    return 'spam';
                }
                return $this->read_at ? 'read' : 'unread';
            },
        );
    }
}
