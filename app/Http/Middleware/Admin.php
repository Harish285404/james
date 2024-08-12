<?php

namespace App\Http\Middleware;
use Illuminate\Http\Request;

use Closure;
use Auth;


class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        

   if (Auth::check()) 
        {

            if (Auth()->user()->role == "1") {

                // return redirect('user');
                return $next($request);

                
            }
            else{

                return redirect('user');
                // return $next($request);

            } 
            
        } else{
                return redirect('/');
            
        }
                // return $next($request);

}
}
