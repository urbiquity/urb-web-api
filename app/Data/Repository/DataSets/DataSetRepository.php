<?php

namespace App\Data\Repository\DataSets;

use App\Data\Models\DataSets\DataSet;
use App\Data\Repository\BaseRepository;

class DataSetRepository extends BaseRepository
{

    /**
     * Defining entity model
     *
     * @var mixed $entity
     */
    protected $data_sets, $meta_index = 'data_sets';

    public function __construct(
        DataSet $dataSets
    ){
        $this->data_sets = $dataSets;
    }

    // region Define

    public function define( $data=[] )
    {
        $result = refresh_model( $this->data_sets, $data );

        if( isset( $data['id'] ) ){
            if( !is_numeric( $data['id'] ) || $data['id'] <= 0 ){
                return $this->httpInternalServerResponse([
                    'title' => 'Invalid Data Sets ID.',
                ]);
            }

            $entity = $this->data_sets->find( $data['id'] );

            if( !$entity ){
                return $this->httpNotFoundResponse([
                    'title' => 'This data set does not exists',
                ]);
            }
        }

        foreach( $this->data_sets->getFillable() as $arr ) {
            if( !isset( $data[$arr] ) ){
                return $this->setResponse([
                    'code' => 500,
                    'title' => "Required field: " . $arr,
                ]);
            }
        }

        foreach( $data as $key => $value ){
            if( in_array( $key, $this->data_sets->getFillable() ) ){
                if( $result->hasAttribute( $key ) ){
                    $result->$key = $value;
                }
            }
        }

        if( !$result->save() ){
            $error_message = $result->errors();

            return $this->httpInternalServerResponse([
                'title' => "Datta se was not defined successfully.",
                'meta' => [
                    'errors' => $error_message,
                ]
            ]);
        }


        return $this->httpSuccessResponse([
            'title' => 'Successfully defined Data set',
            'meta' => [
                $this->meta_index => $result,
            ]
        ]);

    }

    // endregion Define

    // region Delete

    public function delete( $data=[] )
    {
        if( isset( $data['id'] ) ){
            if( !is_numeric( $data['id'] ) || $data['id'] <= 0){
                return $this->httpInternalServerResponse([
                    'title' => 'Invalid ID.',
                ]);
            }
        }

        $params = array_merge( $data, [
            "is_model" => true,
        ]);

        $result = $this->fetch( $params );
        $deleted = $this->entity->withTrashed()->where('id', $data['id'])->first();

        if( !$deleted ){
            return $this->httpNotFoundResponse([
                'title' => 'This entity does not exists',
            ]);
        }

        else if( $deleted->trashed() ){
            return $this->httpSuccessResponse([
                'title' => "This data is not found or may have already been deleted.",
                'meta' => [
                    "id" => $data['id']
                ]
            ]);
        }

        if( !$result->delete() ){
            return $this->httpInternalServerResponse([
                'title' => 'Entity was not deleted successfully.',
                'meta' => [
                    'errors' => $result->errors(),
                ]
            ]);
        }

        return $this->httpSuccessResponse([
            'title' => 'Successfully deleted entity',
            'meta' => [
                'entity' => $result,
            ]
        ]);

    }

    // endregion Delete

    // region Generate

    public function generate(){

    }

    // endregion Generate

    // region Retrieve Data

    public function fetch( $data=[] )
    {
        $params = $data;

        if( isset( $data['id'] ) ){
            if( !is_numeric( $data['id'] ) ){
                return $this->httpInternalServerResponse([
                    'title' => 'Invalid ID',
                ]);
            }

            $params = [
                'single' => true,
                'where' => [
                    [
                        'operator' => '=',
                        'target' => 'id',
                        'value' => $data['id'],
                    ]
                ]
            ];

        }

        $result = $this->fetchGeneric( $params, $this->data_sets );

        // Fetch result meta

        if( !$result ){
            return $this->httpNotFoundResponse([
                'title' => 'This data set does not exist',
            ]);
        }

        if( isset( $data['is_model'] ) && $data['is_model'] === true ){
            return $result;
        }

        return $this->httpSuccessResponse([
            'title' => 'Successfully retrieved data set',
            'meta' => [
                $this->meta_index => $result
            ]
        ]);

    }

    // endregion Retrieve Data

}
