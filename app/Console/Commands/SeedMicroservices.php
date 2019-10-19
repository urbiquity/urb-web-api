<?php

namespace App\Console\Commands;

use App\Console\Commands\Seeders\BaseSeeder;
use App\Console\Commands\Seeders\Entity\EntityRelationshipSeeder;
use Carbon\Carbon;
use Common\Microservices\Entity\EntityMicroservice;
use Common\Microservices\EntityPrograms\EntityProgramMicroservice;
use Common\Microservices\Utilities\Configurations\ConfigurationMicroservice;
use App\Console\Commands\Seeders\Entity\EntitySeeder;

class SeedMicroservices extends \Common\BaseClasses\Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    protected $program_svc, $entity_svc;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        EntityMicroservice $entityMicroservice,
        ConfigurationMicroservice $configurationMicroservice,
        EntityProgramMicroservice $entityProgramMicroservice

    ){
        parent::__construct();
        $this->entity_svc = $entityMicroservice;
        $this->config_svc = $configurationMicroservice;
        $this->program_svc = $entityProgramMicroservice;
    }

    protected $seeder_map = [
        "entity" => EntitySeeder::class,
        "entity.relationships" => EntityRelationshipSeeder::class,
    ];

    public function seed( $target="all" ){
        switch( $target ){
            case  "all":
                foreach( $this->seeder_map as $key => $class ){
                    /**
                     * @var BaseSeeder $instance
                     */
                    $instance = app()->make( $class );
                    $instance->seed();
                }
                break;
        }
    }

    // region PROGRAMS MICROSERVICES SEED

    public function programTypes()
    {
        $program_types = [
            [
                'name'               => 'Platform of Government',
                'description'        => 'President Benigno S. Aquino III signed Executive Order No. 43, s. 2011, thematically organizing the Cabinet into smaller groups called as the Cabinet Clusters. The Cabinet Clusters—composed of Good Governance and Anti-corruption; Human Development and Poverty Reduction; Economic Development; Security, Justice, and Peace; and Climate Change Adaptation and Mitigation—serve as the primary mechanism of the Executive Branch for directing all efforts towards the realization of the Social Contract with the Filipino People and its five key result areas.',
                'status'             => 'active',
            ],
            [
                'name'               => 'Good Governance and Anti-corruption',
                'description'        => 'The Good Governance and Anti-Corruption Cluster shall promote transparency, accountability, participatory governance, and strengthening of public institutions. It shall also work to regain the trust and confidence of the public in government.',
                'status'             => 'active',
            ],
            [
                'name'               => 'Human Development',
                'description'        => 'The Human Development and Poverty Reduction Cluster shall focus on improving the overall quality of life of the Filipino and translating the gains of good governance into direct, immediate, and substantial benefits that will empower the poor and marginalized segments of society.',
                'status'             => 'active',
            ],
            [
                'name'               => 'Economic Development',
                'description'        => 'The Economic Development Cluster shall focus on the promotion of rapid, inclusive, and sustained economic growth.',
                'status'             => 'active',
            ],
            [
                'name'               => 'Security, Justice, and Peace',
                'description'        => 'The Security Cluster shall ensure the preservation of national sovereignty and the rule of law; and focus on the protection and promotion of human rights and the pursuit of a just, comprehensive, and lasting peace.',
                'status'             => 'active',
            ],
            [
                'name'               => 'Environment and Climate Change',
                'description'        => 'The Climate Change Adaptation and Mitigation Cluster shall focus on the conservation, and protection of the environment and natural resources. It shall take the lead in pursuing measures to adapt to and mitigate the effects of climate change on the Philippine archipelago; and undertake all the necessary preparation for both natural and man-made disasters.',
                'status'             => 'active',
            ],
        ];

        foreach ( $program_types as $program_type ) {
            $test = $this->program_svc
                ->call("programs.types.define", $program_type);
        }
        return $test->getTitle();
    }

    public function programs()
    {
        $faker = \Faker\Factory::create();
        $programs = [
            [
                'name'               => 'A Social Contract with the Filipino People',
                'parent_id'          => 0,
                'type_id'            => 1,
                'start_date'         => $faker->dateTimeBetween('+0 days', '+2 years')->format('Y-m-d'),
                'end_date'           => $faker->dateTimeBetween('+0 days', '+2 years')->format('Y-m-d'),
                'cost_allocated'     => $faker->numberBetween(100000,900000),
                'cost_estimated'     => $faker->numberBetween(100000,900000),
                'cost_actual'        => $faker->numberBetween(100000,900000),
                'program_status'     => $faker->randomElement(['proposed', 'implemented']),
                'status'             => 'active',
                'added_by'           => $faker->randomDigit,
                'updated_by'         => $faker->randomDigit

            ],
            [
                'name'               => 'Governance Cluster webpage',
                'parent_id'          => 0,
                'type_id'            => 2,
                'start_date'         => $faker->dateTimeBetween('+1 week', '+1 month')->format('Y-m-d'),
                'end_date'           => $faker->dateTimeBetween('+0 days', '+5 years')->format('Y-m-d'),
                'cost_allocated'     => $faker->numberBetween(100000,900000),
                'cost_estimated'     => $faker->numberBetween(100000,900000),
                'cost_actual'        => $faker->numberBetween(100000,900000),
                'program_status'     => $faker->randomElement(['proposed', 'implemented']),
                'status'             => 'active',
                'added_by'           => $faker->randomDigit,
                'updated_by'         => $faker->randomDigit

            ],
            [
                'name'               => 'Freedom of Information',
                'parent_id'          => 0,
                'type_id'            => 2,
                'start_date'         => $faker->dateTimeBetween('+1 week', '+6 month')->format('Y-m-d'),
                'end_date'           => $faker->dateTimeBetween('+0 days', '+15 years')->format('Y-m-d'),
                'cost_allocated'     => $faker->numberBetween(100000,900000),
                'cost_estimated'     => $faker->numberBetween(100000,900000),
                'cost_actual'        => $faker->numberBetween(100000,900000),
                'program_status'     => $faker->randomElement(['proposed', 'implemented']),
                'status'             => 'active',
                'added_by'           => $faker->randomDigit,
                'updated_by'         => $faker->randomDigit

            ],
            [
                'name'               => 'The People’s Budget',
                'parent_id'          => 0,
                'type_id'            => 2,
                'start_date'         => $faker->dateTimeBetween('+1 week', '+9 month')->format('Y-m-d'),
                'end_date'           => $faker->dateTimeBetween('+0 days', '+8 years')->format('Y-m-d'),
                'cost_allocated'     => $faker->numberBetween(100000,900000),
                'cost_estimated'     => $faker->numberBetween(100000,900000),
                'cost_actual'        => $faker->numberBetween(100000,900000),
                'program_status'     => $faker->randomElement(['proposed', 'implemented']),
                'status'             => 'active',
                'added_by'           => $faker->randomDigit,
                'updated_by'         => $faker->randomDigit

            ],
            [
                'name'               => 'Human Development and Poverty Reduction Cluster',
                'parent_id'          => 0,
                'type_id'            => 3,
                'start_date'         => $faker->dateTimeBetween('+1 week', '+1 month')->format('Y-m-d'),
                'end_date'           => $faker->dateTimeBetween('+0 days', '+3 years')->format('Y-m-d'),
                'cost_allocated'     => $faker->numberBetween(100000,900000),
                'cost_estimated'     => $faker->numberBetween(100000,900000),
                'cost_actual'        => $faker->numberBetween(100000,900000),
                'program_status'     => $faker->randomElement(['proposed', 'implemented']),
                'status'             => 'active',
                'added_by'           => $faker->randomDigit,
                'updated_by'         => $faker->randomDigit

            ],
            [
                'name'               => 'Pantawid Pamilyang Pilipino Program (CCT)',
                'parent_id'          => 0,
                'type_id'            => 3,
                'start_date'         => $faker->dateTimeBetween('+1 week', '+1 month')->format('Y-m-d'),
                'end_date'           => $faker->dateTimeBetween('+300 days', '+5 years')->format('Y-m-d'),
                'cost_allocated'     => $faker->numberBetween(100000,900000),
                'cost_estimated'     => $faker->numberBetween(100000,900000),
                'cost_actual'        => $faker->numberBetween(100000,900000),
                'program_status'     => $faker->randomElement(['proposed', 'implemented']),
                'status'             => 'active',
                'added_by'           => $faker->randomDigit,
                'updated_by'         => $faker->randomDigit

            ],
            [
                'name'               => 'K to 12 Basic Education',
                'parent_id'          => 0,
                'type_id'            => 3,
                'start_date'         => $faker->dateTimeBetween('+1 week', '+1 month')->format('Y-m-d'),
                'end_date'           => $faker->dateTimeBetween('+260 days', '+25 years')->format('Y-m-d'),
                'cost_allocated'     => $faker->numberBetween(100000,900000),
                'cost_estimated'     => $faker->numberBetween(100000,900000),
                'cost_actual'        => $faker->numberBetween(100000,900000),
                'program_status'     => $faker->randomElement(['proposed', 'implemented']),
                'status'             => 'active',
                'added_by'           => $faker->randomDigit,
                'updated_by'         => $faker->randomDigit

            ],
            [
                'name'               => 'Responsible Parenthood',
                'parent_id'          => 0,
                'type_id'            => 3,
                'start_date'         => $faker->dateTimeBetween('+1 week', '+1 month')->format('Y-m-d'),
                'end_date'           => $faker->dateTimeBetween('+10 days', '+9 years')->format('Y-m-d'),
                'cost_allocated'     => $faker->numberBetween(100000,900000),
                'cost_estimated'     => $faker->numberBetween(100000,900000),
                'cost_actual'        => $faker->numberBetween(100000,900000),
                'program_status'     => $faker->randomElement(['proposed', 'implemented']),
                'status'             => 'active',
                'added_by'           => $faker->randomDigit,
                'updated_by'         => $faker->randomDigit

            ],
            [
                'name'               => 'Sin Taxes',
                'parent_id'          => 0,
                'type_id'            => 3,
                'start_date'         => $faker->dateTimeBetween('+1 week', '+1 month')->format('Y-m-d'),
                'end_date'           => $faker->dateTimeBetween('+60 days', '+15 years')->format('Y-m-d'),
                'cost_allocated'     => $faker->numberBetween(100000,900000),
                'cost_estimated'     => $faker->numberBetween(100000,900000),
                'cost_actual'        => $faker->numberBetween(100000,900000),
                'program_status'     => $faker->randomElement(['proposed', 'implemented']),
                'status'             => 'active',
                'added_by'           => $faker->randomDigit,
                'updated_by'         => $faker->randomDigit

            ],
            [
                'name'               => 'Public-Private Partnership',
                'parent_id'          => 0,
                'type_id'            => 4,
                'start_date'         => $faker->dateTimeBetween('+1 week', '+11 month')->format('Y-m-d'),
                'end_date'           => $faker->dateTimeBetween('+0 days', '+25 years')->format('Y-m-d'),
                'cost_allocated'     => $faker->numberBetween(100000,900000),
                'cost_estimated'     => $faker->numberBetween(100000,900000),
                'cost_actual'        => $faker->numberBetween(100000,900000),
                'program_status'     => $faker->randomElement(['proposed', 'implemented']),
                'status'             => 'active',
                'added_by'           => $faker->randomDigit,
                'updated_by'         => $faker->randomDigit

            ],
            [
                'name'               => 'Philippine Development Plan 2011-2016',
                'parent_id'          => 0,
                'type_id'            => 4,
                'start_date'         => $faker->dateTimeBetween('+1 week', '+10 month')->format('Y-m-d'),
                'end_date'           => $faker->dateTimeBetween('+110 days', '+20 years')->format('Y-m-d'),
                'cost_allocated'     => $faker->numberBetween(100000,900000),
                'cost_estimated'     => $faker->numberBetween(100000,900000),
                'cost_actual'        => $faker->numberBetween(100000,900000),
                'program_status'     => $faker->randomElement(['proposed', 'implemented']),
                'status'             => 'active',
                'added_by'           => $faker->randomDigit,
                'updated_by'         => $faker->randomDigit

            ],
            [
                'name'               => 'National Security Policy 2011-2016',
                'parent_id'          => 0,
                'type_id'            => 5,
                'start_date'         => $faker->dateTimeBetween('+3 week', '+10 month')->format('Y-m-d'),
                'end_date'           => $faker->dateTimeBetween('+80 days', '+35 years')->format('Y-m-d'),
                'cost_allocated'     => $faker->numberBetween(100000,900000),
                'cost_estimated'     => $faker->numberBetween(100000,900000),
                'cost_actual'        => $faker->numberBetween(100000,900000),
                'program_status'     => $faker->randomElement(['proposed', 'implemented']),
                'status'             => 'active',
                'added_by'           => $faker->randomDigit,
                'updated_by'         => $faker->randomDigit

            ],
            [
                'name'               => 'PAMANA Program',
                'parent_id'          => 0,
                'type_id'            => 5,
                'start_date'         => $faker->dateTimeBetween('+4 week', '+10 month')->format('Y-m-d'),
                'end_date'           => $faker->dateTimeBetween('+80 days', '+15 years')->format('Y-m-d'),
                'cost_allocated'     => $faker->numberBetween(100000,900000),
                'cost_estimated'     => $faker->numberBetween(100000,900000),
                'cost_actual'        => $faker->numberBetween(100000,900000),
                'program_status'     => $faker->randomElement(['proposed', 'implemented']),
                'status'             => 'active',
                'added_by'           => $faker->randomDigit,
                'updated_by'         => $faker->randomDigit

            ],
            [
                'name'               => 'Project NOAH',
                'parent_id'          => 0,
                'type_id'            => 6,
                'start_date'         => $faker->dateTimeBetween('+2 week', '+11 month')->format('Y-m-d'),
                'end_date'           => $faker->dateTimeBetween('+90 days', '+13 years')->format('Y-m-d'),
                'cost_allocated'     => $faker->numberBetween(100000,900000),
                'cost_estimated'     => $faker->numberBetween(100000,900000),
                'cost_actual'        => $faker->numberBetween(100000,900000),
                'program_status'     => $faker->randomElement(['proposed', 'implemented']),
                'status'             => 'active',
                'added_by'           => $faker->randomDigit,
                'updated_by'         => $faker->randomDigit

            ],
            [
                'name'               => 'National Green Program',
                'parent_id'          => 0,
                'type_id'            => 6,
                'start_date'         => $faker->dateTimeBetween('+1 week', '+3 month')->format('Y-m-d'),
                'end_date'           => $faker->dateTimeBetween('+89 days', '+19 years')->format('Y-m-d'),
                'cost_allocated'     => $faker->numberBetween(100000,900000),
                'cost_estimated'     => $faker->numberBetween(100000,900000),
                'cost_actual'        => $faker->numberBetween(100000,900000),
                'program_status'     => $faker->randomElement(['proposed', 'implemented']),
                'status'             => 'active',
                'added_by'           => $faker->randomDigit,
                'updated_by'         => $faker->randomDigit

            ]
        ];

        foreach ( $programs as $program ) {
            $test = $this->program_svc
                ->call("programs.define", $program);
        }
        return $test->getTitle();
    }

    public function programEnrollments()
    {
        $faker = \Faker\Factory::create();

        for($i = 1; $i <= 30; $i++ ){
              $test = $this->program_svc->call("programs.program_id.enrollments.define",
                  [
                    'program_id'            => $faker->numberBetween(1,15),
                    'entity_id'             => $faker->numberBetween(1,1000),
                    'date_enrolled'         => $faker->dateTimeBetween('+1 week', '+1 month')->format('Y-m-d'),
                    'cost_estimated'        => $faker->numberBetween(1000,20000),
                    'cost_actual'           => $faker->numberBetween(1000,20000),
                    'enrollment_status'     => $faker->randomElement(['active', 'pending']),
                    'status'                => 'active',
                    'added_by'              => $faker->numberBetween(1,15),
                    'updated_by'            => $faker->numberBetween(1,15),
                  ]);
        }

        return $test->getTitle();

    }

    public function programEnrollmentUpdates()
    {
        $faker = \Faker\Factory::create();

        for($i = 1; $i <= 30; $i++ ){
              $test = $this->program_svc->call("programs.program_id.enrollments.enrollment_id.updates.define",
                  [
                      'program_id'         => $faker->numberBetween(1,15),
                      'enrollment_id'      => $faker->numberBetween(1,30),
                      'title'              => $faker->sentence,
                      'description'        => $faker->sentence,
                      'update_type'        => "approved",
                      'status'             => 'active',
                      'added_by'           => $faker->randomDigit,
                      'updated_by'         => $faker->randomDigit
                  ]);
        }

        return $test->getTitle();

    }

    public function programEnrollmentUpdateCosts()
    {
        $faker = \Faker\Factory::create();
        for($i = 1; $i <= 30; $i++ ){
              $test = $this->program_svc->call("programs.program_id.enrollments.enrollment_id.updates.update_id.costs.define",
                  [
                      'program_id'         => $faker->numberBetween(1,15),
                      'enrollment_id'      => $faker->numberBetween(1,30),
                      'update_id'          => $faker->numberBetween(1,10),
                      'amount'             => $faker->numberBetween(1000,10000),
                      'type'               => $faker->randomElement(['credit', 'debit']),
                      'payment_status'     => 'paid',
                      'received_by'        => $faker->randomDigit,
                      'date_paid'          => $faker->dateTimeBetween('+1 week', '+1 month')->format('Y-m-d'),
                      'status'             => 'active',
                  ]);
        }

        return $test->getTitle();
    }

    public function programUpdateCostTransactions()
    {
        $faker = \Faker\Factory::create();
        for($i = 1; $i <= 30; $i++ ){
              $test = $this->program_svc->call("programs.program_id.enrollments.enrollment_id.updates.update_id.costs.cost_id.transactions.define",
                  [
                      'program_id'         => $faker->numberBetween(1,15),
                      'enrollment_id'      => $faker->numberBetween(1,10),
                      'update_id'          => $faker->numberBetween(1,10),
                      'cost_id'            => $faker->numberBetween(1,10),
                      'transaction_id'     => 0,
                  ]);
        }

        return $test->getTitle();
    }

    // endregion PROGRAMS MICROSERVICES SEED

    // region CONFIGURATIONS MICROSERVICE SEED

    public function configurations()
    {
        $faker = \Faker\Factory::create();

        for($i = 1; $i <= 30; $i++ ){
            $test = $this->program_svc->call("configurations.define",
                  [
                      'parent_id'         => $faker->randomDigit,
                      'target_id'         => $faker->randomDigit,
                      'key'               => $faker->realText(10,1),
                      'value'             => $faker->realText(20,1),
                      'full_slug'         => $faker->slug,
                      'app_name'          => $faker->domainName,
                  ]);
        }

        return $test->getTitle();

    }

    public function variables()
    {
        $faker = \Faker\Factory::create();

        for($i = 1; $i <= 30; $i++ ){
              $test = $this->program_svc->call("variables.define",
                  [
                      'key'               => $faker->realText(10,1),
                      'value'             => $faker->realText(20,1),
                      'type'              => $faker->realText(10,1),
                  ]);
        }

        return $test->getTitle();

    }

    public function variableTypes()
    {
        $faker = \Faker\Factory::create();

        for($i = 1; $i <= 30; $i++ ){
            $test = $this->program_svc->call("variables.types.define",
                  [
                      'key'               => $faker->realText(10,1),
                      'value'             => $faker->realText(20,1),
                  ]);
        }

        return $test->getTitle();

    }

    // endregion CONFIGURATIONS MICROSERVICE SEED

    // region ENTITIES MICROSERVICE SEED


    // region WIP - if mugana na ang define sa entities.define

    public function individuals()
    {
        $faker = \Faker\Factory::create();

        $individuals = [
            [
                'full_name'          => $faker->name,
                'first_name'         => $faker->firstName,
                'last_name'          => $faker->lastName,
                'address'            => "sds",
                'email'              => $faker->email,
                'contact_number'     => $faker->phoneNumber,
                'status'             => 'active',
            ]
        ];

        dd($individuals);
        foreach ( $individuals as $individual ) {
            $test = $this->entity_svc
                ->call("entities.define", $individual);
        }
        return $test->getResponse();

    }


    // end region WIP


    // endregion ENTITIES SEED

    public function runPrograms()
    {
        $this->programTypes();
        $this->programs();
        $this->programEnrollments();
        $this->programEnrollmentUpdates();
        $this->programEnrollmentUpdateCosts();
        $this->programUpdateCostTransactions();
    }

    public function runConfig()
    {
        $this->configurations();
        $this->variableTypes();
        $this->variables();

    }

    public function runEntities()
    {
        $this->individuals();

    }



}
