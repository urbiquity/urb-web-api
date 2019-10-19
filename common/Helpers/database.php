<?php

use App\Data\Models\BaseModel;
use Common\BaseClasses\Model;
use Illuminate\Database\Eloquent\Builder;

if (!function_exists("dump_query")) {
    /**
     * @param Builder $model
     * @return string
     */
    function dump_query($model)
    {
        $copy = clone ($model);
        return $copy->getModel()->getRawSql($copy);
    }
}

if (!function_exists("refresh_model")) {
    /**
     * Returns a fresh instance of a given Eloquent Model
     *
     * @param \App\Data\Models\BaseModel $model
     * @param array $data
     * @return mixed
     */
    function refresh_model($model, $data = [])
    {
        $class = $model->getClass();
        $new_model = new $class($data);

        if (!$new_model instanceof Model) {
            return false;
        }

        return $new_model;
    }
}
