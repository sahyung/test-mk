<?php

namespace App\Models;

use App\Traits\UuidsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kost extends Model
{
    use UuidsTrait, SoftDeletes;

    protected $casts = [
        'id' => 'string',
    ];
    protected $hidden = ['user_id', 'created_at', 'updated_at', 'deleted_at', 'available_room_count'];
    protected $appends = ['owner'];
    protected $fillable = [
        'user_id',
        'name',
        'city',
        'price',
        'available_room_count',
        'total_room_count',
    ];

    public function getOwnerAttribute()
    {
        return ($this->hasOne('App\Models\User', 'id', 'user_id')->first()) ? $this->hasOne('App\Models\User', 'id', 'user_id')->first()->serializeLess() : null;
    }

    public function serializeWithAvailability()
    {
        return [
            'id' => $this->id,
            'user_id' => $this->getOwnerAttribute(),
            'name' => $this->name,
            'city' => $this->city,
            'price' => $this->price,
            'available_room_count' => $this->available_room_count,
            'total_room_count' => $this->total_room_count,
        ];
    }
}
