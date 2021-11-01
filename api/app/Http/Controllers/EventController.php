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

    /**
     * Using __invoke to simplify route call
     * @param Request $request
     * @return mixed
     */
    public function __invoke(Request $request)
    {
        $data = $request->json()->all();
        $method = $data['type'];

        // here the type (method) was already checked and is not invalid or null
        return $this->service->$method($data);
    }
}
