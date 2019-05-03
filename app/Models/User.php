<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

/**
 * @OA\Schema(type="object", title="User", description="user model", required={"name", "email", "password", "password_confirmation", "client_id", "client_secret"})
 * @OA\Property(
 *     type="string",
 *     description="Name of the user",
 *     property="name"
 * ),
 * @OA\Property(
 *     type="string",
 *     description="Email of the user",
 *     property="email"
 * ),
 * @OA\Property(
 *     type="string",
 *     description="Password of the user",
 *     property="password",
 *     format="password"
 * ),
 * @OA\Property(
 *     type="string",
 *     description="Password confirmarion of the user",
 *     property="password_confirmation",
 *     format="password"
 * ),
 * @OA\Property(
 *     type="integer",
 *     description="Id of the client fromt which this user is being registered",
 *     property="client_id"
 * ),
 * @OA\Property(
 *     type="string",
 *     description="Secret key of the client id",
 *     property="client_secret",
 * )
 */
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
    public static function ValidationBook($except = [], $append = [])
    {
        $book = ['rules' => [], 'messages' => []];
        $book['rules'] = [
            'user.name' => 'required|string',
            'user.email' => 'required|email|unique:users,email',
            'user.password' => 'required|min:8',
            'user.password_confirmation' => 'required|min:8|same:user.password',

            //Extra data for register
            'user.client_id' => 'required|exists:oauth_clients,id',
            'user.client_secret' => 'required|exists:oauth_clients,secret'
        ];
        $book['messages'] = [
            'user.name.required' => 'El nombre del usuario es requerido',
            'user.name.string' => 'El nombre del usuario tiene que ser un texto.',

            'user.email.required' => 'El email del usuario es requerido',
            'user.email.email' => 'El email del usuario tiene que ser valido.',
            'user.email.unique' => 'Este email ya está registrado.',

            'user.password.required' => 'Se require una contraseña',
            'user.password.min' => 'La contraseña debe tener al menos 8 caracteres',

            'user.password_confirmation.required' => 'Se requiere la confirmación del password',
            'user.password_confirmation.same' => 'El password y su confirmación no coinciden',

            // Extras
            'user.client_id.required' => 'Se requiere el id del cliente',
            'user.client_secret.required' => 'Se requiere el secret del cliente y debe serl el secret del cliente',
        ];
        if (!empty($except)) {
            $except = array_flip($except);
            $book['rules'] = array_diff_key($book['rules'], $except);
        }
        if (!empty($append)) {
            $book = array_merge_recursive($book, $append);
        }
        return $book;
    }

}
