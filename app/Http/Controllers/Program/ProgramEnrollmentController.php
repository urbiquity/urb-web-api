<?php

namespace App\Http\Controllers\Program;

use Common\Microservices\Entity\EntityMicroservice;
use Common\Microservices\EntityPrograms\EntityProgramMicroservice;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;


/**
 * Class DefinitionAdvanceController
 * @package App\Http\Controllers\Workforce
 */
class ProgramEnrollmentController extends BaseController
{
    protected $program_enrollment, $entity_svc;

    /**
     * Instantiate class with $program_enrollmentRepository
     *
     * @param ProgramRepository $program_enrollmentRepository
     */
    public function __construct(
        EntityProgramMicroservice $entityProgramMicroservice,
        EntityMicroservice $entityMicroservice
    ){
        $this->program_enrollment = $entityProgramMicroservice;
        $this->entity_svc = $entityMicroservice;
    }

    // region All

    /**
     * @param Request $request
     * @return mixed
     */
    public function all( $program_id, Request $request )
    {
        return $this->absorb(
            $this->program_enrollment->call("programs.program_id.enrollments.all",[
                "program_id" => $program_id,
            ])
        )->json();

    }

    //endregion All

    // region Define

    /**
     * @param Request $request
     * @return mixed
     */
    public function define( Request $request )
    {
        return $this->absorb(
            $this->program_enrollment->call("programs.program_id.enrollments.define",
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
    public function delete( $program_id, $id )
    {
        return $this->absorb(
            $this->program_enrollment->call("programs.program_id.enrollments.delete.id", [
                "program_id" => $program_id,
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
    public function fetch($program_id, $id)
    {
        return $this->absorb(
            $this->program_enrollment->call("programs.program_id.enrollments.fetch.id", [
                "program_id" => $program_id,
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
    public function search( $program_id, Request $request)
    {
        return $this->absorb(
            $this->program_enrollment->call("programs.program_id.enrollments.search", [
                    "program_id" => $program_id,
                    $request->all()
            ])
        )->json();
    }

    // endregion Search Data

    public function individualsOfProgram( Request $request , $program_id )
    {
        $data = $request->all();

        $data['program_id'] = $program_id;


        $program = $this->program_enrollment->call("programs.fetch.id", [
            "id" => $program_id,
        ]);

        $entity_program = $this->program_enrollment->call("programs.program_id.enrollments.all", [
            "program_id" => $program_id
        ])->getMeta();

        if( $entity_program == null ){
            return $this->setResponse( [
                'title' => " No program found.",
            ])->json();
        }

        $this->absorb($program);

        $array = [];

        foreach( $entity_program['program_enrollment'] as $key => $value){

            if( $value->program_id == $program_id ){
                $entity_program = $this->entity_svc->call("entities.fetch.id", [
                    'id' => $value->entity_id
                ])->getMeta();

                array_push($array, $entity_program);
            }
        }

        $meta = ["programs" => $this->getMeta()];
        $meta['entities'] = $array;

        return $this->httpSuccessResponse([
            'title' => "Successfully retrieved individuals by programs",
            'meta' => $meta
        ])->json();

    }

}
