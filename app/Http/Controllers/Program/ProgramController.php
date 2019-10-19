<?php

namespace App\Http\Controllers\Program;

use Common\Microservices\EntityPrograms\EntityProgramMicroservice;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;


/**
 * Class DefinitionAdvanceController
 * @package App\Http\Controllers\Workforce
 */
class ProgramController extends BaseController
{
    protected $program;

    /**
     * Instantiate class with $programRepository
     *
     * @param ProgramRepository $programRepository
     */
    public function __construct(
        EntityProgramMicroservice $entityProgramMicroservice
    ){
        $this->program = $entityProgramMicroservice;
    }

    // region All

    /**
     * @param Request $request
     * @return mixed
     */
    public function all( Request $request ){
        return $this->absorb(
            $this->program->call("programs.all",
                $request->all()
            )
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
            $this->program->call("programs.define",
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
    public function delete( $id ){
        return $this->absorb(
            $this->program->call("programs.delete.id", [
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
    public function fetch( $id ){
        return $this->absorb(
            $this->program->call("programs.fetch.id", [
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
    public function search( Request $request){
        return $this->absorb(
            $this->program->call("programs.search",
                $request->all()
            )
        )->json();
    }

    // endregion Search Data

}
