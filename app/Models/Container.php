<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(type="object", title="Container", description="Container model", required={"name", "height", "radius"})
 * @OA\Property(
 *     type="string",
 *     description="Name of the container",
 *     property="name"
 * ),
 * @OA\Property(
 *     type="number",
 *     description="Height of the container in meters",
 *     property="height"
 * ),
 * @OA\Property(
 *     type="number",
 *     description="Radius of the container in meters",
 *     property="radius"
 * )
 */
class Container extends Model
{

    /**
     * The attributes that should be mutated to dates.
     *
     * @var  array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['dummy' => 'boolean'];

    /**
     * The attributes that are mass assignable.
     *
     * @var  array
     */
    protected $fillable = [
        'device_id',
        'dummy',
        'height',
        'name',
        'radius',
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
            'container.device_id' => 'string|nullable|exists:devices,id|unique:containers,device_id',
            'container.dummy' => 'boolean',
            'container.height' => 'required|numeric|between:0,99999999999999999999999999.9999',
            'container.name' => 'string|required',
            'container.radius' => 'required|numeric|between:0,99999999999999999999999999.9999',
            'container.user_id' => 'required|exists:users,id'
        ];
        $book['messages'] = [
            'container.device_id.string' => 'El id del dispositivo debe ser un texto',
            'container.device_id.exists' => 'El id del dispositivo no es válido',
            'container.device_id.unique' => 'El id del dispositivo ya ha sido usado',

            'container.dummy.boolean' => 'El campo dummy debe ser un valor booleano',

            'container.height.required' => 'Se requiere la altura del contenedor',
            'container.height.numeric' => 'La altura del contenedor debe ser un número',
            'container.height.between' => 'La altura del conteneder debe tener un valor mínimo de 0 y un máximo de 99999999999999999999999999.9999',

            'container.name.string' => 'El nombre del contenedor debe ser un texto',
            'container.name.required' => 'El nombre del contenedor es requerido',

            'container.radius.required' => 'Se requiere el radio del contenedor',
            'container.radius.numeric' => 'El radio del contenedor debe ser un número',
            'container.radius.between' => 'El radio del conteneder debe tener un valor mínimo de 0 y un máximo de 99999999999999999999999999.9999',

            'container.user_id.required' => 'Se requiere el id del usuario',
            'container.user_id.exists' => 'El id del usuario debe ser un id válido'
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

    /**
     * Defines the relationship of a container and his creator
     */
    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Defines the relationship of a container with his measures
     */
    public function measures() {
        return $this->hasMany('App\Models\Measure');
    }

}
