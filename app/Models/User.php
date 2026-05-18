<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasRoles, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'managerId',
        'office_id',
        'is_locked',
        'login_attempts',
    ];
    
    protected $casts = [
        'is_locked' => 'boolean',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Many-to-One: User belongs to an Office
    public function office()
    {
        return $this->belongsTo(Offices::class, 'office_id');
    }

    // One-to-Many: User owns many inventories
    public function ownedInventories()
    {
        return $this->hasMany(Inventory::class, 'user_id');
    }

    // Many-to-Many: User can manipulate many inventories
    public function manipulatableInventories()
    {
        return $this->belongsToMany(Inventory::class)->withTimestamps();
    }

    public function admin()
    {
        return $this->hasMany(User::class, 'managerId');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'managerId');
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
