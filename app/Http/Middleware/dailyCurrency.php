<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class dailyCurrency
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {

            $user = auth()->user();

            $date = time();

            if($date > $user->last_currency + 86400) {

                $currentCash = $user->cash;
                $currentCoins = $user->coins;

                if($user->membership == 0) {
                    $newCash = $currentCash + 5;
                    $newCoins = $currentCoins + 10;
                } elseif($user->membership == 1) {
                    $newCash = $currentCash + 15;
                    $newCoins = $currentCoins + 30;
                } elseif($user->membership == 2) {
                    $newCash = $currentCash + 30;
                    $newCoins = $currentCoins + 60;
                }

                User::where('id', $user->id)->update(array('last_currency' => $date, 'cash' => $newCash, 'coins' => $newCoins));
            }

        }
        return $next($request);
    }
}
