<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the hotels this user belongs to.
     */
    public function hotels(): BelongsToMany
    {
        return $this->belongsToMany(\App\Domain\Hotel\Models\Hotel::class)->withTimestamps();
    }

    /**
     * Get the housekeeping tasks assigned to this user.
     */
    public function housekeepingTasks(): HasMany
    {
        return $this->hasMany(\App\Domain\Operations\Models\HousekeepingTask::class, 'assigned_to');
    }

    /**
     * Get the maintenance tickets assigned to this user.
     */
    public function maintenanceTickets(): HasMany
    {
        return $this->hasMany(\App\Domain\Operations\Models\MaintenanceTicket::class, 'assigned_to');
    }
}
