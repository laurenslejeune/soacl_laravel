<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class APIKey
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
        //Tutorial:
        //http://www.jeffreyteruel.com/article/22
        
        if ($request->apikey == '') 
        {

            return redirect('/');

        }
        else
        { 

            $users = User::where('access_key', $request->apikey)->count();

            if ($users != 1)
            {
                return response("Invalid access key");
            } 
            else 
            {
              return $next($request);
            }
        }
        
        return $next($request);
    }
}
