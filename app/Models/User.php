<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Returns an array that contains two indexes:
     * 'rules' for the validation
     * 'messages' messages given by the validation
     *
     * @return array
     **/
    public static function ValidationBook()
    {
        $data = [];
        $data['rules'] = [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|min:8|same:password',

            //Extra data for register
            'client_id' => 'required|exists:oauth_clients,id',
            'client_secret' => 'required|exists:oauth_clients,secret'
        ];
        $data['messages'] = [
            'name.required' => 'El nombre del usuario es requerido',
            'name.string' => 'El nombre del usuario tiene que ser un texto.',

            'email.required' => 'El email del usuario es requerido',
            'email.email' => 'El email del usuario tiene que ser valido.',
            'email.unique' => 'Este email ya está registrado.',

            'password.required' => 'Se require una contraseña',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',

            'password_confirmation.required' => 'Se requiere la confirmación del password',
            'password_confirmation.same' => 'El password y su confirmación no coinciden',

            // Extras
            'client_id.required' => 'Se requiere el id del cliente',
            'client_secret.required' => 'Se requiere el secret del cliente y debe serl el secret del cliente',
        ];
        return $data;
    }

}
