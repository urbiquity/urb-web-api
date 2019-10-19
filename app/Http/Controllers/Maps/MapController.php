<?php

namespace App\Http\Controllers\Maps;

use App\Data\Repository\Maps\MapsRepository;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class MapController extends BaseController{

    protected $map;

    public function __construct(
        MapsRepository $mapsRepository
    ){
        $this->map = $mapsRepository;
    }

    // region define

    public function define( Request $request )
    {
        return $this->absorb(
            $this->map->define(
                $request->all()
            )
        )->json();
    }

    // endregion define

    // region delete

    public function delete( Request $request, $id )
    {
        return $this->absorb(
            $this->map->delete([
                'id' => $id,
            ])
        )->json();
    }

    // endregion delete

    // region retrieve

    public function fetch( Request $request, $id )
    {
        return $this->absorb(
            $this->map->fetch([
                'id' => $id,
            ])
        )->json();
    }

    // endregion retrieve

}