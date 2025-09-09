<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'capital',
        'area',
        'currencies',
        'languages',
        'flag',
        'region',
        'subregion',
        'population',
        'added_by_user_id',
        'source'
    ];

    protected $casts = [
        'currencies' => 'array',
        'languages' => 'array',
        'area' => 'float',
        'population' => 'integer',
    ];

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by_user_id');
    }

    public function scopeAddedByUsers($query)
    {
        return $query->where('source', 'user_added');
    }

    public function scopeFromApi($query)
    {
        return $query->where('source', 'api');
    }

    public function getCurrenciesTextAttribute(): string
    {
        if (!$this->currencies) {
            return 'N/A';
        }

        $currencies = [];
        foreach ($this->currencies as $code => $currency) {
            $currencies[] = $currency['name'] ?? $code;
        }

        return implode(', ', $currencies);
    }

    public function getLanguagesTextAttribute(): string
    {
        if (!$this->languages) {
            return 'N/A';
        }

        return implode(', ', array_values($this->languages));
    }
}
