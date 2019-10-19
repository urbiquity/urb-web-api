<?php


namespace App\Console\Commands\Seeders\Entity;

use App\Console\Commands\Seeders\BaseSeeder;
use Common\Microservices\Entity\EntityMicroservice;
use Illuminate\Support\Arr;


class EntitySeeder extends BaseSeeder
{
    protected $address_file = "";
    protected $file = "";

    protected $entity_svc;

    protected $column_map = [
        "last_name",
        "first_name",
        "middle_name",
        "full_name",
    ];

    protected $except_column_map = [
        "middle_name",
    ];

    protected $age_brackets = [
        [
            "min" => 0,
            "max" => 15,
        ],
        [
            "min" => 16,
            "max" => 30,
        ],
        [
            "min" => 31,
            "max" => 45,
        ],
        [
            "min" => 46,
            "max" => 60,
        ],
        [
            "min" => 61,
            "max" => 75,
        ],
    ];

    protected $email_list = [];

    protected $phone_prefixes = [];


    public function __construct(
        EntityMicroservice $entityMicroservice
    ){
        $this->entity_svc = $entityMicroservice;

        $path = app_path("Console/Commands/Seeders/Entity/");
        $this->address_file = $path . "barangay_list.csv";
        $this->file = $path . "entity_list.csv";

        $this->email_list = get_email_domains();
        $this->phone_prefixes = get_ph_phone_prefixes();
    }

    public function seed( ){
        $limit = 50000;

        foreach( $this->makeIndividuals() as $key => $individual ){
            if( $key < $limit ){
                $result = $this->entity_svc->call("entities.define", $individual )->getResponse();
                echo $result['title'] . " " .
                    $result['description'] . " " .
                    json_encode($result['meta']) . " " .
                    "\n";
            }
        }
    }

    protected function makeIndividuals( $path ){
        $data = read_csv( $this->file, ["delimiter" => ","]);

        unset( $data[0] );

        $address = Arr::flatten(read_csv( $this->address_file, ["delimiter" => ","]));

        $individuals = [];
        foreach( $data as $datum ){
            $individual = [];

            foreach( $datum as $key => $value ){
                if( !in_array( $this->column_map[ $key ], $this->except_column_map ) ) {
                    $individual[ $this->column_map[ $key ] ] = $value;
                }
            }

            $individual['address'] = $this->makeAddress( $address );
            $individual['age'] = $this->makeAge();
            $individual['contact_number'] = $this->makePhone();
            $individual['email'] = $this->makeMail( $individual );
            $individual['gender'] = $this->makeGender();
            $individual['status'] = 'active';

            $individuals[] = $individual;
        }

        return $individuals;
    }

    protected function makeAddress( $addresses ){
        $faker = \Faker\Factory::create();
        return $faker->streetAddress . ", " . $addresses[ rand(0,count($addresses)-1) ];
    }

    protected function makeAge(){
        $bracket = $this->age_brackets[rand(0, count( $this->age_brackets) - 1 )];
        return rand( $bracket['min'], $bracket['max']);
    }

    protected function makeGender(){
        return rand(0,1) ? "male" : "female";
    }

    protected function makeMail( $individual ){
        $name = acronym( $individual['full_name'] ) . (rand(0,1) ? "." : "_") . $individual['last_name'];

        return strtolower($name) . "@" . $this->email_list[rand(0,count( $this->email_list) - 1)];
    }

    protected function makePhone(){
        return $this->phone_prefixes[ rand(0,count($this->phone_prefixes)-1) ] . rand(1000000,9999999);
    }

}