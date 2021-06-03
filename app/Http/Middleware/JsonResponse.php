<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Handle HTTP requests and changes the response as necessary.
 * 
 * @author Vitor Ferreira <vitorg_s@hotmail.com>
 */
class JsonResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $response = $next($request);
        $statusCode = $response->getStatusCode();

        if ($request->method() == 'POST' && $statusCode == 200) {
            $statusCode = 201;
        }
        
        try {
            $body = $response->content();
        } catch (\Throwable $th) {
            $body = $response->getContent();
        }
        $data = new \stdClass();
        $data->error = false;
        if ($statusCode >= 400) {
            $data->error = true;
        }

        $json = json_decode($body);

        $data->timestamp = time();
        $data->elasted = microtime(true) - LARAVEL_START;
        $data->body = isset($json) ? $json : new \stdClass();
        $data->status_code = $statusCode;

        $response->setContent(json_encode($data));

        return $response;
    }
}
