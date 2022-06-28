<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Middleware;

class PreventRequestsDuringMaintenance extends Middleware
{
    /**
     * The application implementation.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * The URIs that should be reachable while maintenance mode is enabled.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function handle($request, Closure $next)
    {
        if (Setting::where('maintenance_enabled', '1')->get()->first() &&
            !in_array($request->getClientIp(), [
                '97.92.205.231', //kyle
                '104.189.145.55', //jordan
                '68.35.160.15', //sid
                '166.194.132.36',
            ]))
        {
            if(url()->current() != "https://dev.brixoro.com/site/offline")
            {
                return redirect()->route('maintenance.index');
            }
        } else {
            if(url()->current() == "https://dev.brixoro.com/site/offline")
            {
                return redirect('/');
            }
        }

        return $next($request);
    }
}
