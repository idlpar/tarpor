<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CaptureAdTrackingParameters
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $trackingParameters = [
            'utm_source',
            'utm_medium',
            'utm_campaign',
            'utm_term',
            'utm_content',
            'fbclid',
            'gclid',
            'ttclid',
        ];

        $adTrackingData = [];
        foreach ($trackingParameters as $param) {
            if ($request->has($param)) {
                $adTrackingData[$param] = $request->query($param);
            }
        }

        if (!empty($adTrackingData)) {
            $request->session()->put('ad_tracking_data', $adTrackingData);
        }

        return $next($request);
    }
}
