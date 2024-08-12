<?php

namespace App\Http\Middleware;
use Illuminate\Http\Request;
use Auth;
use Closure;

class User
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

            if (Auth()->user()->role == "2") {
                // return redirect('user');
                return $next($request);

                
            }
            else{

                return redirect('/');
                // return $next($request);

            } 
            
        } else{
                return redirect('/');
            
        }
            
    }
}
