<?php

namespace App\Http\Controllers;

use App\Services\AccountService;
use Exception;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    private $service;

    public function __construct(AccountService $service) {
        $this->service = $service;
    }

    /**
     * Using __invoke() to simplify route call
     * @param Request $request
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function __invoke(Request $request)
    {
        try {
            if ($request->has('account_id')) {
                $account = $this->service->getAccount($request->get('account_id'));

                if ($account) {
                    return response($account['balance'], 200);
                }
            }

            return response(0, 404);

        } catch(Exception $exception) {
            return response($exception->getMessage(), 404);
        }
    }
}
