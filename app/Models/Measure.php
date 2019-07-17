<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(type="object", title="Measure", description="Measure of a sensor for a given container", required={"height"})
 * @OA\Property(
 *     type="number",
 *     description="Height/distance between the water and the sensor",
 *     property="height"
 * ),
 */
class Measure extends Model
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
        'container_id',
        'height',
        'volume',
        // Only for test seeding
        'created_at'
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
            'measure.height' => 'required|numeric|between:0,99999999999999999999999999.9999',
            'measure.container_id' => 'required|exists:containers,id'
        ];
        $book['messages'] = [
            'measure.height.required' => 'Se requiere la altura de de la medida',
            'measure.height.numeric' => 'La altura de la medida debe ser un número',
            'measure.height.between' => 'La altura de la medida debe tener un valor mínimo de 0 y un máximo de 99999999999999999999999999.9999',

            'measure.container_id.required' => 'Se requiere el id del usuario',
            'measure.container_id.exists' => 'El id del usuario debe ser un id válido'
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
     * Defines the relationship of a measure with his container
     */
    public function container() {
        return $this->belongsTo('App\Models\Container');
    }

}
