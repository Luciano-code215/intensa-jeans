<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'activo', 'telefono'])]
#[Hidden(['password', 'remember_token'])]


class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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
            'activo' => 'boolean',
        ];
    }

    static function registrarUsuario($name, $email, $password, $role = 'user')
    {
        return self::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => $role,
            'activo' => true,
        ]);
    }

    static function registrarAdmin($name, $email, $password)
    {
        return self::registrarUsuario($name, $email, $password, 'admin');
    }

    static function login($email, $password)
    {
        $user = self::where('email', $email)->first();

        if ($user && \Hash::check($password, $user->password)) {
            return $user;
        }

        return null;
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
