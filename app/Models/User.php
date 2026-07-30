<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
codex/establish-project-context-for-lms-development-jszrnb
use Illuminate\Database\Eloquent\Relations\HasMany;
main
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, HasRoles, Notifiable, TwoFactorAuthenticatable;

    protected $fillable = ['name', 'email', 'password'];
codex/establish-project-context-for-lms-development-jszrnb

    protected $hidden = ['password', 'remember_token', 'two_factor_recovery_codes', 'two_factor_secret'];

    protected function casts(): array
    {
        return ['email_verified_at' => 'datetime', 'password' => 'hashed'];
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'professor_id');
    }

    public function sectionsTeaching(): HasMany
    {
        return $this->hasMany(CourseSection::class, 'teacher_id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'student_id');
    }
    protected $hidden = ['password', 'remember_token', 'two_factor_recovery_codes', 'two_factor_secret'];
    protected function casts(): array { return ['email_verified_at' => 'datetime', 'password' => 'hashed']; }
main
}
