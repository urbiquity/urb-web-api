<?php

namespace App\Http\Controllers\Program;

use Common\Microservices\EntityPrograms\EntityProgramMicroservice;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;


/**
 * Class ProgramTypeController
 * @package App\Http\Controllers\Workforce
 */
class ProgramTypeController extends BaseController
{
    protected $program_type;

    /**
     * Instantiate class with $programTypeRepository
     *
     * @param ProgramTypeRepository $programTypeRepository
     */
    public function __construct(
        EntityProgramMicroservice $entityProgramMicroservice
    ){
        $this->program_type = $entityProgramMicroservice;
    }

    // region All

    /**
     * @param Request $request
     * @return mixed
     */
    public function all( Request $request ){
        return $this->absorb(
            $this->program_type->call("programs.types.all",
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
            $this->program_type->call("programs.types.define",
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
            $this->program_type->call("programs.types.delete.id", [
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
            $this->program_type->call("programs.types.fetch.id", [
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
            $this->program_type->call("programs.types.search",
                $request->all()
            )
        )->json();
    }

    // endregion Search Data

}
