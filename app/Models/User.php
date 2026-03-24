<?php

namespace App\Models;

<<<<<<< HEAD
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
=======
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
>>>>>>> 3863a09b394c58216ab17e6ce1358e41955aa5e3
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
<<<<<<< HEAD
     */
     protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'otp',
        'is_verified',
        'username',
        'phone',
        'avatar',
        'bio',
=======
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
>>>>>>> 3863a09b394c58216ab17e6ce1358e41955aa5e3
    ];

    /**
     * The attributes that should be hidden for serialization.
<<<<<<< HEAD
=======
     *
     * @var list<string>
>>>>>>> 3863a09b394c58216ab17e6ce1358e41955aa5e3
     */
    protected $hidden = [
        'password',
        'remember_token',
<<<<<<< HEAD
        'otp',
=======
>>>>>>> 3863a09b394c58216ab17e6ce1358e41955aa5e3
    ];

    /**
     * Get the attributes that should be cast.
<<<<<<< HEAD
=======
     *
     * @return array<string, string>
>>>>>>> 3863a09b394c58216ab17e6ce1358e41955aa5e3
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
<<<<<<< HEAD
            'otp_expires_at' => 'datetime',
            'password' => 'hashed',
            'is_verified' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the profile associated with the user.
     */
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is instructor.
     */
    public function isInstructor(): bool
    {
        return $this->role === 'instructor';
    }

    /**
     * Check if user is student.
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }
}

=======
            'password' => 'hashed',
        ];
    }
}
>>>>>>> 3863a09b394c58216ab17e6ce1358e41955aa5e3
