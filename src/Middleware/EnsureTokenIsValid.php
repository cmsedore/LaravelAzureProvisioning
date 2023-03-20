<?php

namespace RobTrehy\LaravelAzureProvisioning\Middleware;


use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure(Request): (Response) $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->bearerToken() !== config('azureprovisioning.token')) {
            Log::debug('Unauthorized request: ' . $request->bearerToken(),$request->all() );
            return response('Unauthorized.', 403);
        }

        ray($request->method()." ".$request->url(),json_encode($request->all()));
        Log::debug($request->method()." ".$request->url(),$request->all());

        return $next($request);
    }

    public function terminate($request, $response): void
    {
        ray('RESPONSE...',$request->method()." ".$request->url(),$response)->purple();
        Log::debug('RESPONSE... '. $request->method()." ".$request->url(),$request->all());

    }
}
