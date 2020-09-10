<?php

declare(strict_types=1);

namespace Napp\Xray\Middleware;

use Closure;
use Napp\Xray\Xray;

class RequestTracing
{
    /**
     * @var \Napp\Xray\Xray
     */
    private $xray;

    public function __construct(Xray $xray)
    {
        $this->xray = $xray;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    /**
     * Terminates a request/response cycle.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response  $response
     */
    public function terminate($request, $response): void
    {
        $this->xray->submitHttpTracer($response);
    }
}
