<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'contacts';

    protected $fillable = [
        'client_id',
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'client_id' => 'integer',
            'status' => 'boolean',
        ];
    }

    /**
     * Get the client associated with the contact.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }
}
