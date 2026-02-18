<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Infrastructure\Tenancy\CurrentHotel;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCurrentHotel
{
    public function __construct(
        protected CurrentHotel $currentHotel
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        if (!$this->currentHotel->has() && $request->user()) {
            $firstHotel = $request->user()->hotels()->first();
            
            if ($firstHotel) {
                $this->currentHotel->set($firstHotel);
            }
        }

        return $next($request);
    }
}
