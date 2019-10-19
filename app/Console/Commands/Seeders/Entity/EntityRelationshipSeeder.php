<?php


namespace App\Console\Commands\Seeders\Entity;


use App\Console\Commands\Seeders\BaseSeeder;
use Common\Microservices\Entity\EntityMicroservice;

class EntityRelationshipSeeder extends BaseSeeder
{
    protected $entity_svc;
    public function __construct( EntityMicroservice $entityMicroservice ){
        // TODO: LOAD DISTINCT LAST NAMES
        $this->entity_svc = $entityMicroservice;
    }

    public function seed(){

    }
}