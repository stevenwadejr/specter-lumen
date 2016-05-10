<?php

namespace App\Http\Middleware;

use Closure;
use HelpScout\Specter\Specter;
use Illuminate\Http\Response;
use LogicException;

class SpecterMiddleware
{
    /**
     * JSON must have this property to trigger processing
     *
     * @var string
     */
    protected $specterTrigger = '__specter';

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param int $limit
     * @return mixed
     */
    public function handle($request, Closure $next, $limit = 1)
    {
        /** @var Response $response */
        $response = $next($request);

        $fixture = @json_decode($response->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new LogicException(
                'Failed to parse json string. Error: '.json_last_error_msg()
            );
        }

        // We will not process files without the Specter trigger, and instead
        // return an unchanged response.
        if (!array_key_exists($this->specterTrigger, $fixture)) {
            return $response;
        }

        // Process the fixture data, using a seed in case the designer wants
        // a repeatable result.
        $seed = $request->header('SpecterSeed', 0);
        $specter = new Specter($seed);

        $json = [];

        for ($i = 0; $i < $limit; $i++) {
            $json[] = $specter->substituteMockData($fixture);
        }

        $data = json_encode($limit === 1 ? $json[0] : $json);

        return $response->setContent($data);
    }
}
