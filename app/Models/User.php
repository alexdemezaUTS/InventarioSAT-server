<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'users'; // Personalizado
    protected $primaryKey = 'idUsuario'; // Personalizado

    public $timestamps = false;

    protected $fillable = [
        'nombreUsuario',
        'primerApellido',
        'segundoApellido',
        'correo',
        'contrasena',
        'celular',
        'tipo_usuario',
        'idAlmacen'
    ];

    protected $hidden = [
        'contrasena',
    ];
    protected $casts = [
        'idAlmacen' => 'integer',
    ];

    public function getAuthPassword()
    {
        return $this->contrasena;
    }
       public function almacen()
    {
        return $this->belongsTo(Almacen::class, 'idAlmacen')->withDefault();
    }
}


// namespace App\Models;

// // use Illuminate\Contracts\Auth\MustVerifyEmail;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Foundation\Auth\User as Authenticatable;
// use Illuminate\Notifications\Notifiable;

// class User extends Authenticatable
// {
//     /** @use HasFactory<\Database\Factories\UserFactory> */
//     use HasFactory, Notifiable;

//     /**
//      * The attributes that are mass assignable.
//      *
//      * @var list<string>
//      */
//     protected $fillable = [
//         'name',
//         'email',
//         'password',
//     ];

//     /**
//      * The attributes that should be hidden for serialization.
//      *
//      * @var list<string>
//      */
//     protected $hidden = [
//         'password',
//         'remember_token',
//     ];

//     /**
//      * Get the attributes that should be cast.
//      *
//      * @return array<string, string>
//      */
//     protected function casts(): array
//     {
//         return [
//             'email_verified_at' => 'datetime',
//             'password' => 'hashed',
//         ];
//     }
// }
