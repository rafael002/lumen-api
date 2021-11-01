<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EnsureEventIsValid
{
    /**
     * Validate if json payload is valid before controller. In case of error, return response error.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $json = $request->json()->all();
        if (!in_array($json['type'], ['transfer', 'withdraw', 'deposit']))
            return response('Event type not allowed', 400);

        $validator = null;
        switch($json['type']) {
            case 'transfer':
                $validator = Validator::make($json, [
                    'origin' => 'required|numeric',
                    'amount' => 'required|numeric|gt:0',
                    'destination' => 'required|numeric'
                ]);
                break;
            case 'withdraw':
                $validator = Validator::make($json, [
                    'origin' => 'required|numeric',
                    'amount' => 'required|numeric|gt:0'
                ]);
                break;
            case 'deposit':
                $validator = Validator::make($json, [
                    'destination' => 'required|numeric',
                    'amount' => 'required|numeric|gt:0'
                ]);
            break;
        }

        if ($validator->fails()) { // TODO melhorar esse retorno
            return response('Bad Request', 400);
        }

        return $next($request);
    }
}
