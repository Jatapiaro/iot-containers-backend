<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Container extends Model
{

    /**
     * The attributes that should be mutated to dates.
     *
     * @var  array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var  array
     */
    protected $fillable = [
        'device_id',
        'dummy',
        'name',
        'user_id',
        'volume',
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
            'container.device_id' => 'string|nullable',
            'container.dummy' => 'boolean',
            'container.name' => 'string|required',
            'container.user_id' => 'required|exists:users,id',
            'container.volume' => 'required|numeric|between:0,99999999999999999999999999.9999'
        ];
        $book['messages'] = [
            'container.device_id.string' => 'El id del dispositivo debe ser un texto',

            'container.dummy.boolean' => 'El campo dummy debe ser un valor booleano',

            'container.name.string' => 'El nombre del contenedor debe ser un texto',
            'container.name.required' => 'El nombre del contenedor es requerido',

            'container.user_id.required' => 'Se requiere el id del usuario',
            'container.user_id.exists' => 'El id del usuario debe ser un id válido',

            'container.volume.required' => 'Se requiere la capacidad/volumen del contenedor',
            'container.volume.numeric' => 'El volumen del contenedor debe ser un número',
            'container.volume.between' => 'El volumen del conteneder debe tener un valor mínimo de 0 y un máximo de 99999999999999999999999999.9999'
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
