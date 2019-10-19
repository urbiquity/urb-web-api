<?php

namespace App\Http\Controllers\Individuals;

use Common\Microservices\EntityPrograms\EntityProgramMicroservice;
use Common\Microservices\Entity\EntityMicroservice;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class IndividualController extends BaseController
{
    /**
     * Defining individual repository
     *
     * @var IndividualRepository $individual_repo
     */
    protected $individual_repo;

    public function __construct(
        EntityMicroservice $individual_repo,
        EntityProgramMicroservice $entityProgramMicroservice
    ){
        $this->individual_repo = $individual_repo;
        $this->program_svc = $entityProgramMicroservice;
    }

    /**
     * Fetch all individuals
     *
     * @param Request $request
     * @return mixed
     */

    public function all( Request $request ){
        $request_data = $request->all();

        if( isset( $request->all()['search']['value'] ) ){
            $request_data['fuzzy_search'] = $request_data['search']['value'];
        }

        return $this->absorb(
            $this->individual_repo->call("entities.all",
                $request_data
            )
        )->json();
    }


    // region Define

    /**
     * @param Request $request
     * @return mixed
     */
    public function define( Request $request )
    {
        $data = $request->all();

        if( isset($data['baranggay']) && isset( $data['city']) ){
            $baranggay = explode("-", $data['baranggay'] );
            $data['baranggay'] = $baranggay[0];
            $data['city'] = $baranggay[1];
        }

        return $this->absorb(
            $this->individual_repo->call("entities.define",
                $data
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
    public function delete( $id )
    {
        return $this->absorb(
            $this->individual_repo->call("entities.delete.id", [
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
    public function fetch( $id )
    {
        return $this->absorb(
            $this->individual_repo->call("entities.fetch.id", [
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
    public function search( Request $request )
    {
        return $this->absorb(
            $this->individual_repo->call("entities.search",
                $request->all()
            )
        )->json();
    }

    public function individualProgram( Request $request , $individual_id )
    {
        $data = $request->all();

        $data['individual_id'] = $individual_id;

        $individual = $this->individual_repo->call("entities.fetch.id", [
            "id" => $individual_id,
        ]);

        $entity_program = $this->program_svc->call("programs.enrollments.entity_id", [
            "entity_id" => $individual_id
        ])->getMeta();

        if( $entity_program == null ){
            return $this->setResponse( [
                'title' => "No program found.",
            ])->json();
        }
        $this->absorb($individual);

        $individual = $individual->getMeta();
        $meta = array_merge(["individual" => $individual], $entity_program);


        return $this->httpSuccessResponse([
            'title' => "Successfully retrieved program enrollment by individual",
            'meta' => $meta
        ])->json();

    }

    // endregion Search Data

}
