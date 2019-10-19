<?php 

namespace Common\BaseClasses;
    
abstract class Link {

    public $next;

    abstract public function handle( $data, $params );

    public function succeedWith( $next ){
        $this->next = $next;
    }

    public function next( $data, $params ){
        
        if( $this->next ){
            return $this->next->handle( $data, $params);
        }
        
        return $data ;
    }
}
