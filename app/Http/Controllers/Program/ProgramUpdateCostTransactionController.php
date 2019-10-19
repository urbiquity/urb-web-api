<?php

namespace App\Http\Controllers\Program;

use Common\Microservices\EntityPrograms\EntityProgramMicroservice;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;


/**
 * Class ProgramUpdateCostTransactionsController
 * @package App\Http\Controllers\Programs;
 */
class ProgramUpdateCostTransactionController extends BaseController
{
    protected $program_update_cost_transaction;

    /**
     * Instantiate class with $program_update_cost_transactionRepository
     *
     * @param ProgramRepository $program_update_cost_transactionRepository
     */
    public function __construct(
        EntityProgramMicroservice $entityProgramMicroservice
    ){
        $this->program_update_cost_transaction = $entityProgramMicroservice;
    }

    // region All

    /**
     * @param Request $request
     * @return mixed
     */
    public function all( $program_id, $enrollment_id, $update_id, $cost_id, Request $request)
    {
        return $this->absorb(
            $this->program_update_cost_transaction->call("programs.program_id.enrollments.enrollment_id.updates.update_id.costs.cost_id.transactions.all",[
                "program_id" => $program_id,
                "enrollment_id" => $enrollment_id,
                "update_id" => $update_id,
                "cost_id" => $cost_id,
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
        $data = array_merge( $request->all(), [
            "transaction_id" => 0,
        ]);
        return $this->absorb(
            $this->program_update_cost_transaction->call("programs.program_id.enrollments.enrollment_id.updates.update_id.costs.cost_id.transactions.define",
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
    public function delete( $program_id, $enrollment_id, $update_id, $cost_id, $id )
    {
        return $this->absorb(
            $this->program_update_cost_transaction->call("programs.program_id.enrollments.enrollment_id.updates.update_id.costs.cost_id.transactions.delete.id", [
                "program_id" => $program_id,
                "enrollment_id" => $enrollment_id,
                "update_id" => $update_id,
                "cost_id" => $cost_id,
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
    public function fetch( $program_id, $enrollment_id, $update_id, $cost_id, $id )
    {
        return $this->absorb(
            $this->program_update_cost_transaction->call("programs.program_id.enrollments.enrollment_id.updates.update_id.costs.cost_id.transactions.fetch.id", [
                "program_id" => $program_id,
                "enrollment_id" => $enrollment_id,
                "update_id" => $update_id,
                "cost_id" => $cost_id,
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
    public function search( $program_id, $enrollment_id, $update_id, $cost_id, Request $request){
        return $this->absorb(
            $this->program_update_cost_transaction->call("programs.program_id.enrollments.enrollment_id.updates.update_id.costs.cost_id.transactions.search", [
                "program_id" => $program_id,
                "enrollment_id" => $enrollment_id,
                "update_id" => $update_id,
                "cost_id" => $cost_id,
                $request->all()
            ])
        )->json();
    }

    // endregion Search Data

}
