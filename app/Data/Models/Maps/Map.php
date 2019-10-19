<?php

namespace App\Data\Models\Maps;

use Common\BaseClasses\Model;

class Map extends Model{

    protected $table = 'maps';
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
