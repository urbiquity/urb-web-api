<?php

namespace Common\Middleware;

use Closure;
use Common\Utilities\Cipher;
use Common\Traits\Instances\Response;

class VerifyAppAccess
{
    protected $salt="traxiontech.net";


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        // Here is a trick for Middleware not to check against auth/login
        // or something u dont want to check against middleware

        if(false && $request->is('test/*')){

            return $next($request);

        }

        if(
            $request->header("Security-Source-Name") == null ||
            $request->header("Security-Source-Hash") == null ||
            $request->header("Security-Target-Hash") == null
        ){
            return $this->invalidateAccess( "Headers are null.");
        }

        if(
            !(
                $request->header("security-target-hash") ===
                $this->getHash( config( env("APP_SLUG") ) )
            ) ||
            !(
                $request->header("security-source-hash") ===
                $this->getHash( config( $request->header("security-source-name") ) )
            )
        ){
            return $this->invalidateAccess("Invalid hashes", [
            ]);
        }

        return $next($request);
    }

    protected function getHash( $value ){
        return Cipher::hash( $value );
    }

    protected function invalidateAccess( $title = "You do not have access to this endpoint.", $meta=[]){
        return (new Response)->httpUnauthorizedResponse([
            "title" => $title,
            "meta" => $meta,
        ])->asJson()->getResponse();
    }
}
