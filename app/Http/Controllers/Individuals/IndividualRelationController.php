<?php

namespace App\Http\Controllers\Individuals;

use Common\Microservices\Entity\EntityMicroservice;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class IndividualRelationController extends BaseController
{
    protected $individual_relations_repo;

    public function __construct(
        EntityMicroservice $individualRelationsRepository){
        $this->individual_relations_repo = $individualRelationsRepository;
    }
    /**
     * Fetch all individual relationships
     *
     * @param Request $request
     * @return mixed
     */

     public function all( $target_entity_id, Request $request )
     {
         return $this->absorb(
             $this->individual_relations_repo->call("entities.target_entity_id.relations.all", [
                 "target_entity_id" => $target_entity_id
             ])
         )->json();

     }

     // region Define

     /**
      * @param Request $request
      * @return mixed
      */
     public function define( Request $request )
     {
         return $this->absorb(
             $this->individual_relations_repo->call("entities.target_entity_id.relations.define",
                 $request->all()
             )
         )->json();
     }

     // endregion Define

     // region Delete

     /**
      * @param Request $request
      * @param $id
      * @return mixed
      */
     public function delete( $target_entity_id, $id )
     {
         return $this->absorb(
             $this->individual_relations_repo->call("entities.target_entity_id.relations.delete.id", [
                 "target_entity_id" => $target_entity_id,
                 "id" => $id,
             ])
         )->json();
     }

     // endregion Delete

     // region Retrieve Data

     /**
      * @param Request $request
      * @param $id
      * @return mixed
      */
     public function fetch( $target_entity_id, $id )
     {
         return $this->absorb(
             $this->individual_relations_repo->call("entities.target_entity_id.relations.fetch.id", [
                 "target_entity_id" => $target_entity_id,
                 "id" => $id,
             ])
         )->json();
     }

     // endregion Retrieve Data

     // region Search Data

     /**
      * @param Request $request
      * @return mixed
      */
     public function search( $target_entity_id, Request $request)
     {
         return $this->absorb(
             $this->individual_relations_repo->call("entities.target_entity_id.relations.search", [
                 "target_entity_id" => $target_entity_id,
                 $request->all()
             ])
         )->json();
     }


     // endregion Search Data

 }
