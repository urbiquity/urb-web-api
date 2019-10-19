<?php

namespace App\Http\Controllers\Families;

use Common\Microservices\Entity\EntityMicroservice;
use Common\Microservices\EntityPrograms\EntityProgramMicroservice;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;

class FamilyMemberController extends BaseController
{
    protected $family_members_repo;

    public function __construct(
        EntityMicroservice $familyMembersRepository,
        EntityProgramMicroservice $entityProgramMicroservice
    ){
        $this->family_members_repo = $familyMembersRepository;
        $this->program_svc = $entityProgramMicroservice;
    }

    public function all( $genealogy_id, Request $request )
    {
        return $this->absorb(
            $this->family_members_repo->call("genealogies.genealogy_id.members.all", [
                    "genealogy_id" => $genealogy_id
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
            $this->family_members_repo->call("genealogies.genealogy_id.members.define",
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
    public function delete( $genealogy_id, $id )
    {
        return $this->absorb(
            $this->family_members_repo->call("genealogies.genealogy_id.members.delete.id", [
                "genealogy_id" => $genealogy_id,
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
    public function fetch( $genealogy_id, $id )
    {
        return $this->absorb(
            $this->family_members_repo->call("genealogies.genealogy_id.members.fetch.id", [
                "genealogy_id" => $genealogy_id,
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
    public function search( $genealogy_id, Request $request)
    {
        return $this->absorb(
            $this->family_members_repo->call("genealogies.genealogy_id.members.search", [
                "genealogy_id" => $genealogy_id,
                $request->all()
            ])
        )->json();
    }

    // endregion Search Data


    public function familyPrograms( Request $request , $family_id )
    {
        $data = $request->all();

        $data['family_id'] = $family_id;

        $result = [];
        $program = [];

        $family_members = $this->family_members_repo->call("genealogies.genealogy_id.members.all", [
            "genealogy_id" => $family_id,
        ])->getMeta();

        if( !$family_members ){
            return $this->setResponse( [
                'title' => "No Members found.",
            ])->json();
        }

        foreach( $family_members['entities'] as $key => $value ){
            if( $value->genealogy_id == $family_id ){
                    $entity_program = $this->program_svc->call("programs.enrollments.entity_id", [
                        'entity_id' =>  $value->entity_id
                    ])->getMeta();

                    if( $entity_program ) {
                        foreach ($entity_program['program'] as $values_ ) {
                            array_push($program, $values_);
                        }
                    }
            }
        }

        foreach( $program as $values ){
            if( !isset($result[$values->program_id]) ){
                $result[$values->program_id]['name'] = $values->name;
                $result[$values->program_id]['cost_estimated'] = $values->cost_estimated;
                $result[$values->program_id]['cost_actual'] = $values->cost_actual;
            }else{
                $result[$values->program_id]['cost_estimated'] += $values->cost_estimated;
                $result[$values->program_id]['cost_actual'] += $values->cost_actual;
            }

        }

        $result = array_values($result);

        if( !$program ){
            return $this->httpNotFoundResponse([
                'code' => 404,
                'title' =>  "No Program found."
            ])->json();
        }

        return $this->httpSuccessResponse([
            'title' => "Successfully retrieved program enrollment by Family",
            'meta' => [
                'program' => $result,
            ]
        ])->json();

    }

 }
