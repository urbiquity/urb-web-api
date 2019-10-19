<?php

namespace App\Http\Controllers\Program;

use Common\Microservices\EntityPrograms\EntityProgramMicroservice;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;


/**
 * Class ProgramEnrollmentUpdateController
 * @package App\Http\Controllers\Workforce
 */
class ProgramEnrollmentUpdateController extends BaseController
{
    protected $program_enrollment_update;

    /**
     * Instantiate class with $program_enrollment_updateRepository
     *
     * @param ProgramRepository $program_enrollment_updateRepository
     */
    public function __construct(
        EntityProgramMicroservice $entityProgramMicroservice
    ){
        $this->program_enrollment_update = $entityProgramMicroservice;
    }

    // region All

    /**
     * @param Request $request
     * @return mixed
     */
    public function all($program_id, $enrollment_id, Request $request){
        return $this->absorb(
            $this->program_enrollment_update->call("programs.program_id.enrollments.enrollment_id.updates.all",[
                "program_id" => $program_id,
                "enrollment_id" => $enrollment_id,
            ])
        )->json();

    }

    //endregion All

    // region Define

    /**
     * @param Request $request
     * @return mixed
     */
    public function define( Request $request ){
        return $this->absorb(
            $this->program_enrollment_update->call("programs.program_id.enrollments.enrollment_id.updates.define",
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
    public function delete($program_id, $enrollment_id, $id){
        return $this->absorb(
            $this->program_enrollment_update->call("programs.program_id.enrollments.enrollment_id.updates.delete.id", [
                "program_id" => $program_id,
                "enrollment_id" => $enrollment_id,
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
    public function fetch( $program_id, $enrollment_id, $id )
    {
        return $this->absorb(
            $this->program_enrollment_update->call("programs.program_id.enrollments.enrollment_id.updates.fetch.id", [
                "program_id" => $program_id,
                "enrollment_id" => $enrollment_id,
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
    public function search( $program_id, $enrollment_id, Request $request )
    {
        return $this->absorb(
            $this->program_enrollment_update->call("programs.program_id.enrollments.enrollment_id.updates.search", [
                "program_id" => $program_id,
                "enrollment_id" => $enrollment_id,
                $request->all()
            ])
        )->json();
    }

    // endregion Search Data

}
