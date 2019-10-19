<?php

namespace App\Http\Controllers\Program;

use Common\Microservices\EntityPrograms\EntityProgramMicroservice;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;


/**
 * Class ProgramEnrollmentUpdateController
 * @package App\Http\Controllers\Workforce
 */
class ProgramEnrollmentUpdateCostController extends BaseController
{
    protected $program_enrollment_update_cost;

    /**
     * Instantiate class with $program_enrollment_update_costRepository
     *
     * @param ProgramRepository $program_enrollment_update_costRepository
     */
    public function __construct(
        EntityProgramMicroservice $entityProgramMicroservice
    ){
        $this->program_enrollment_update_cost = $entityProgramMicroservice;
    }

    // region All

    /**
     * @param Request $request
     * @return mixed
     */
    public function all( $program_id, $enrollment_id, $update_id, Request $request)
    {
        return $this->absorb(
            $this->program_enrollment_update_cost->call("programs.program_id.enrollments.enrollment_id.updates.update_id.costs.all",[
                "program_id" => $program_id,
                "enrollment_id" => $enrollment_id,
                "update_id" => $update_id,
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
            $this->program_enrollment_update_cost->call("programs.program_id.enrollments.enrollment_id.updates.update_id.costs.define",
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
    public function delete( $program_id, $enrollment_id, $update_id, $id )
    {
        return $this->absorb(
            $this->program_enrollment_update_cost->call("programs.program_id.enrollments.enrollment_id.updates.update_id.costs.delete.id", [
                "program_id" => $program_id,
                "enrollment_id" => $enrollment_id,
                "update_id" => $update_id,
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
    public function fetch( $program_id, $enrollment_id, $update_id, $id )
    {
        return $this->absorb(
            $this->program_enrollment_update_cost->call("programs.program_id.enrollments.enrollment_id.updates.update_id.costs.fetch.id", [
                "program_id" => $program_id,
                "enrollment_id" => $enrollment_id,
                "update_id" => $update_id,
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
    public function search( $program_id, $enrollment_id, $update_id, Request $request){
        return $this->absorb(
            $this->program_enrollment_update_cost->call("programs.program_id.enrollments.enrollment_id.updates.update_id.costs.search", [
                "program_id" => $program_id,
                "enrollment_id" => $enrollment_id,
                "update_id" => $update_id,
                $request->all()
            ])
        )->json();
    }

    // endregion Search Data

}
