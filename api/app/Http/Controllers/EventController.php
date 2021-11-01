<?php

namespace App\Http\Controllers;

use App\Services\AccountService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    private $service;

    public function __construct(AccountService $service) {
        $this->service = $service;
    }

    /** TODO remove
     * OBS: Using __invoke to simplify route call
     */
    public function __invoke(Request $request)
    {
        $data = $request->json()->all();
        $method = $data['type'];

        // here the type was already checked and is not invalid or null
        return $this->service->$method($data);
    }
}
