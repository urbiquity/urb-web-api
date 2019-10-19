<?php

namespace Common\Controllers;

use Common\BaseClasses\Controller;
use Common\BaseClasses\Link;

class DefaultResponseController extends Controller {
    public function notFound(){
        return $this->httpNotFoundResponse()->json();
    }
    public function forbidden(){
        return $this->httpForbiddenResponse()->json();
    }
}