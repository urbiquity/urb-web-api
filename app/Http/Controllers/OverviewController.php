<?php

namespace App\Http\Controllers;

use Common\Microservices\EntityPrograms\EntityProgramMicroservice;
use Common\Microservices\Entity\EntityMicroservice;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;


/**
 * Class DefinitionAdvanceController
 * @package App\Http\Controllers\Workforce
 */
class OverviewController extends BaseController
{
    protected $program, $entity;

    /**
     * Instantiate class with $programRepository
     *
     * @param ProgramRepository $programRepository
     */
    public function __construct(
        EntityProgramMicroservice $entityProgramMicroservice,
        EntityMicroservice $entityMicroservice
    ){
        $this->program = $entityProgramMicroservice;
        $this->entity = $entityMicroservice;
    }

    //  region DONE

    /**
     * @param Request $request
     * @return mixed
     */
    public function totalRegisteredFamilies( Request $request )
    {
        return $this->absorb(
            $this->entity->call("genealogies.registered_families",
                $request->all()
            )
        )->json();
    }


    public function ageDistribution( Request $request )
    {
        return $this->absorb(
            $this->entity->call("entities.age_distribution",
                $request->all()
            )
        )->json();
    }


    public function totalGenderCount( Request $request )
    {
        return $this->absorb(
            $this->entity->call("entities.gender_count",
                $request->all()
            )
        )->json();
    }

    public function totalIndividuals( Request $request )
    {
        return $this->absorb(
            $this->entity->call("entities.total_individuals",
                $request->all()
            )
        )->json();
    }

    public function programStatus( Request $request )
    {
        return $this->absorb(
            $this->program->call("programs.program_status",
                $request->all()
            )
        )->json();
    }

    public function sumOfCost( Request $request )
    {
        return $this->absorb(
            $this->program->call("programs.sum_of_cost",
                $request->all()
            )
        )->json();
    }
    //   endregion DONE

//    region TO DO NEXT
    public function programUpdateCostFromTo( Request $request , $program_id, $enrollment_id, $update_id)
    {
        return $this->absorb(
            $this->program->call("programs.update_cost", [
                $request->all(),
                'program_id' => $program_id,
                'enrollment_id' => $enrollment_id,
            ])
        )->json();
    }


    public function totalCostLastWeek( Request $request, $program_id, $enrollment_id, $update_id )
    {
        return $this->absorb(
            $this->program->call("programs.program_id.enrollments.enrollment_id.updates.update_id.costs.total_cost_last_week", [
                $request->all(),
                'program_id' => $program_id,
                'enrollment_id' => $enrollment_id,
                'update_id' => $update_id,
            ])
        )->json();
    }

    public function totalCostThisWeek( Request $request, $program_id, $enrollment_id, $update_id)
    {
        return $this->absorb(
            $this->program->call("programs.program_id.enrollments.enrollment_id.updates.update_id.costs.total_cost_this_week", [
                $request->all().
                'program_id' => $program_id,
                'enrollment_id' => $enrollment_id,
                'update_id' => $update_id,
            ])
        )->json();
    }

    public function programUpdateActivityLogs( Request $request , $program_id, $enrollment_id)
    {
        return $this->absorb(
            $this->program->call("programs.update_activity_logs", [
                    $request->all(),
                    'program_id' => $program_id,
                    'enrollment_id' => $enrollment_id,
            ])
        )->json();
    }
//    endregion TO DO NEXT

}
