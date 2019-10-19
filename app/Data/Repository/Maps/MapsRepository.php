<?php

namespace App\Data\Repository\Maps;

use App\Data\Models\Maps\Map;
use App\Data\Repository\BaseRepository;

class MapsRepository extends BaseRepository
{
    protected $map, $meta_index = 'map';

    protected $fillable = [
    ];

    protected $searchable = [
        'id',
    ];

    public function __construct(
        Map $mapModel
    ){
        $this->map = $mapModel;
    }

    // region Define

    public function define( $data=[] )
    {
        $result = null;

        if( isset( $data['id'] ) ){
            if( !is_numeric( $data['id'] ) || $data['id'] <= 0 ){
                return $this->httpInternalServerResponse([
                    'title' => "Invalid map ID.",
                ]);
            }
        }

        foreach( $this->map->getFillable() as $column ){
            if( !isset( $data[$column] ) ){
                return $this->setResponse([
                    'code' => 500,
                    'title' => $column." is not set."
                ]);
            }
        }

        if( isset( $data['id'] ) ){
            $result  = $this->map->find($data['id']);
            if( !$result){
                return $this->httpNotFoundResponse([
                    'title' => "This map does not exists",
                ]);
            }
        } else {

            $result = refresh_model( $this->map, $data );

        }

        foreach( $data as $key => $value ){
            if( in_array( $key, $this->map->getFillable() ) ){
                if( $result->hasAttribute( $key ) ){
                    $result->$key = $value;
                }
            }
        }

        if( !$result->save() ){
            $error_message = $result->errors();

            return $this->httpInternalServerResponse([
                'title' => "Map definition was not successful.",
                'meta' => [
                    'errors' => $error_message,
                ]
            ]);
        }

        return $this->httpSuccessResponse([
            'title' => "Successfully defined map.",
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

        $deleted = $this->map->withTrashed()->where('id', $data['id'])->first();

        if( !$deleted ){
            return $this->httpNotFoundResponse([
                'title' => 'This map does not exists',
            ]);
        }

        else if( $deleted->trashed() ){
            return $this->httpSuccessResponse([
                'title' => "This map is not found or may have already been deleted.",
                'meta' => [
                    "id" => $data['id']
                ]
            ]);
        }

        if( !$result->delete() ){
            return $this->httpInternalServerResponse([
                'title' => 'Map was not deleted successfully.',
                'meta' => [
                    'errors' => $result->error(),
                ]
            ]);
        }

        return $this->httpSuccessResponse([
            'title' => 'Successfully deleted map',
            'meta' => [
                'meta' => $result,
            ]
        ]);

    }

    // endregion Delete

    // region Retrieve Data

    public function fetch( $data=[] )
    {
        $params = $data;
        $meta = [];

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

        $result = $this->fetchGeneric( $params, $this->map );
        // Fetch result meta

        if( !$result ){
            return $this->httpNotFoundResponse([
                'title' => 'This map does not exist',
            ]);
        }

        if( isset( $data['is_model'] ) && $data['is_model'] === true ){
            return $result;
        }

        $meta = [
            'coordinate' => $result,
            'meta_data' => [],
        ];

        if( isset( $data['paginate'] ) && is_numeric( $data['paginate'] ) && $data['paginate'] > 0 ){
            $meta = $result;
        }

        return $this->httpSuccessResponse([
            'title' => 'Successfully retrieved map',
            'meta' => $meta
        ]);
    }

    // endregion Retrieve Data

}
