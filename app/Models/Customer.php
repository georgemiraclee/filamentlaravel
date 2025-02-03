<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'image',
        'status',      // Active / Inactive
        'dob',         // Date of Birth
        'address',
        'verified',    // Boolean: 1 (verified), 0 (not verified)
        'notes',       // Rich Text Editor notes
    ];

    protected $casts = [
        'dob' => 'date',
        'verified' => 'boolean',
    ];

    /**
     * Relasi contoh: Customer bisa memiliki banyak pesanan
     */
}
