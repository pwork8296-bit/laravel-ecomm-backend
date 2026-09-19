<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';

    protected $fillable = [
        'name',
        'website_name',
        'website_url',
        'domain',
        'logo',
        'default_meta_title',
        'default_meta_description',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }

    /**
     * Get the blogs belonging to this client.
     */
    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class, 'client_id', 'id');
    }

    /**
     * Get the contacts belonging to this client.
     */
    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class, 'client_id', 'id');
    }
}
