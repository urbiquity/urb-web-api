<?php

namespace App\Http\Controllers\Families;

use Common\Microservices\Entity\EntityMicroservice;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;

class FamilyController extends BaseController
{
    protected $family_repo;

    public function __construct(
        EntityMicroservice $familyRepository
    ){
        $this->family_repo = $familyRepository;
    }

    public function all( Request $request )
    {
        return $this->absorb(
            $this->family_repo->call("genealogies.all",
                $request->all()
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
        return $this->absorb(
            $this->family_repo->call("genealogies.define",
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
    public function delete( $id )
    {
        return $this->absorb(
            $this->family_repo->call("genealogies.delete.id", [
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
            $this->family_repo->call("genealogies.fetch.id", [
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
    public function search( Request $request)
    {
        return $this->absorb(
            $this->family_repo->call("genealogies.search",
                $request->all()
            )
        )->json();
    }

    // endregion Search Data



 }
