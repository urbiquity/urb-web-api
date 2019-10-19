<?php

namespace App\Data\Models\DataSets;

use Common\BaseClasses\Model;

class DataSet extends Model{

    protected $table = 'data_sets';
    protected $primaryKey = 'id';

    protected $fillable = [
        'description'

    ];
    protected $searchable = [
        'id',
        'description'
    ];

    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at'];
}
