<?php

namespace Common\BaseClasses;

use Illuminate\Database\Eloquent\Model as LaravelModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;

/**
 * Class BaseModel
 * @package App\Data\Models
 *
 */
abstract class Model extends LaravelModel
{
    use SoftDeletes; //use softdelete traits

    protected $base_appends = [
        'id_hash',
    ];
    protected $conversions = [];
    private $errors;
    protected $rules = [];
    protected $searchable = [];

    /**
     * Define model constructor
     *
     * Appends id_hash attribute
     *
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        $this->append($this->base_appends);
    }

    /**
     * TODO: Not yet implemented
     * Converts a user-end column to actual model column
     */
    public function convertFrom($from)
    {
        if (array_key_exists($from, $this->conversions)) {
            return $this->conversions[$from];
        }

        return $from;
    }

    /**
     * TODO: Not yet implemented
     * Converts a model-based column to user-end column
     */
    public function convertTo($to)
    {
        foreach ($this->conversions as $key => $value) {
            if ($value === $to) {
                return $key;
            }
        }
    }

    /**
     * Returns array of error messages from model validation
     *
     * @return array
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Returns the name of the current class being called
     *
     * @return void
     */
    public function getClass()
    {
        return static::class;
    }

    /**
     * Returns a raw sql statement
     *
     * @param object $builder
     * @return void
     */
    public function getRawSql($builder)
    {
        $sql = $builder->toSql();
        foreach ($builder->getBindings() as $binding) {
            $value = is_numeric($binding) ? $binding : "'" . $binding . "'";
            $sql = preg_replace('/\?/', $value, $sql, 1);
        }
        return $sql;
    }

    /**
     * Returns a boolean flag whether target fields are in searchable fields
     * of a certain model
     *
     * @param [type] $target
     * @return boolean
     */
    public function isSearchable($target)
    {
        return in_array($target, $this->searchableColumns());
    }

    /**
     * Initializes a new class and returns an instantiated model of the model
     *
     * @param array $data
     * @param object $model
     * @return object
     */
    public function init($data = [], $model = null)
    {
        $model = $model == null ? $this : $model;

        $class = $model->getClass();
        $new_model = new $class($data);

        if (!$new_model instanceof Model) {
            return false;
        }

        return $new_model;
    }

    /**
     * Returns array of fillable fields as defined in each model
     *
     * @param array $data
     * @return array
     */
    public function pullFillable($data = [])
    {
        if (!$this->fillable || (is_array($this->fillable) && empty($this->fillable))) {
            $data = [];
        } else {
            $temp = [];

            foreach ((array) $data as $column => $value) {
                if (in_array($column, $this->fillable)) {
                    $temp[$column] = $value;
                }
            }

            $data = $temp;
        }

        return $data;
    }

    /**
     * @param \App\Data\Models\BaseModel $model
     * @param array $data
     * @return mixed
     */
    public function refreshModel($model = null, $data = [])
    {
        $model = $model === null ? $this : $model;

        return refresh_model($model, $data);
    }

    /**
     * Overrides the built-in save and returns a boolean flag
     * indicating validation rules are passed, fields are properly filled,
     * and successfully executed
     *
     * @param array $options
     * @return boolean
     */
    public function save(array $options = [])
    {
        if (!$this->validate($options)) {
            return false;
        }

        try {
            parent::fill($options);
            parent::save();

            return true;
        } catch(\Illuminate\Database\QueryException $e){
            $error_message = substr(
                $e->getMessage(),
                strpos($e->getMessage(),"Field"),
                strpos($e->getMessage(),"doesn't have a default value") - 9 );
            $this->errors = $error_message;
            return false;
            // Note any method of class PDOException can be called on $ex.
        } catch (\Exception $e) {

            $this->errors = $e->getMessage();
            return false;
        }
    }

    /**
     * Returns the searchable fields (columns) of a certain model (referring to table)
     *
     * @return array
     */
    public function searchableColumns()
    {
        return (array) $this->searchable;
    }

    /**
     * Returns a boolean flag that indicates whether the validation succeeds
     * or fails
     *
     *  @param array $data
     *  @return boolean
     */
    public function validate($data)
    {
        // make a new validator object
        $validate = Validator::make($data, $this->rules);

        if ($validate->fails()) {
            // set errors and return false
            $this->errors = $validate->errors()->first();
            return false;
        }

        // validation pass

        return true;
    }

    /**
     * Appends hashed ID attribute to all models
     *
     * @return string
     */
    public function getIdHashAttribute()
    {
        return sessioned_hash($this->getKey());
    }

    public function hasAttribute($attr)
    {
        return array_key_exists($attr, $this->attributes);
    }
}
