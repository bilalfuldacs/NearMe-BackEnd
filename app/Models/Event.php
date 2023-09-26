<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EventImages;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; 
class Event extends Model
{
    use HasFactory;
    protected $fillable = [
        'Name',
        'Type',
        'Location',
        'Gender',
        'TotalPeople',
        'FromDate',
        'ToDate',
        'AgeGroup',
        'Country',
        'City',
        'Hausnumber',
        'PostalCode',
        'EventDescription',
        'Email',
        'Phone',
        'Whatsapp',
        'user_id',
    ];
    
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function images(): HasMany
    {
        return $this->hasMany(EventImages::class);
    }
}
