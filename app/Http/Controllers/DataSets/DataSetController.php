<?php

namespace App\Http\Controllers\DataSets;

use App\Data\Repository\DataSets\DataSetRepository;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class DataSetController extends BaseController{

    protected $data_sets;

    public function __construct(
        DataSetRepository $dataSetsRepository ){
        $this->data_sets = $dataSetsRepository;
    }

    // region Define

    public function define( Request $request )
    {
        return $this->absorb(
            $this->data_sets->define( $request->all() )
        )->json();
    }

    // endregion Define

    // region Delete

    public function delete( $id )
    {
        return $this->absorb(
            $this->data_sets->delete( [
                'id' => $id,
            ] )
        )->json();
    }

    // endregion Delete

    // region Generate Datasets

    public function generate( Request $request )
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://earthengine.googleapis.com/api/thumb?thumbid=bc77b079c8ecd07cd668c576c22b83a4&token=f46049aefb7366d558b7112c309de556');
        dd( $response );
        $data = $request->all();
        return $this->absorb(
            $this->data_sets->generate(
                $data
            )
        )->json();
    }

    // endregion Generate Datasets

    // region Retrieve Data

    public function fetch( $id )
    {

        return $this->absorb(
            $this->data_sets->fetch( [
                'id' => $id,
            ] )
        )->json();
    }


    // endregion Retrieve Data

}
