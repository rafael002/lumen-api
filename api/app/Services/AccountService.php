<?php

namespace App\Services;

use App\Repositories\AccountRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AccountService
{
    private $repository;

    public function __construct(AccountRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Gets account's data
     * @param $id
     * @return mixed
     */
    public function getAccount($id)
    {
        return $this->repository->get($id);
    }

    public function transfer(Array $data)
    {
        $origin = $this->getAccount($data['origin']);

        if (!$origin) {
            return response(0, ResponseAlias::HTTP_NOT_FOUND);
        }

        if ($origin['balance'] < $data['amount']) {
            return response()->json([
                'description' => 'Bad Request',
                'error' => 'Origin account balance is insufficient'
            ], ResponseAlias::HTTP_BAD_REQUEST);
        }

        DB::beginTransaction();
        try {
            $this->withdraw([
                'origin' => $data['origin'],
                'amount' => $data['amount']
            ]);

            $this->deposit([
                'destination' => $data['destination'],
                'amount' => $data['amount']
            ]);

            $destination = $this->getAccount($data['destination']);

            DB::commit();
            return response($this->jsonFormatterForTest([
                'origin' => [
                    'id' => strval($origin['id']),
                    'balance' => $origin['balance'] - $data['amount']
                ],
                'destination' => [
                    'id' => strval($destination['id']),
                    'balance' => $destination['balance']
                ]
            ]), ResponseAlias::HTTP_CREATED);
        } catch (Exception $exception) {
            DB::rollBack();
            return response()->json([
                'description' => 'Internal Server Error',
                'error' => $exception->getMessage()
            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Laravel json formatter does not add some whitespaces required by tests, this method does.
     * @param array $data
     * @return string
     */
    private function jsonFormatterForTest(array $data): string
    {
        return str_replace(':{',': {', str_replace(',',', ',json_encode($data)));
    }

    public function withdraw(Array $data)
    {
        $response = $this->accountModify([
            'id' => $data['origin'],
            'amount' => $data['amount'] * -1,
            'create' => false
        ], 'origin');

        if (!$response) {
            $response = response(0, ResponseAlias::HTTP_NOT_FOUND);
        }

        return $response;
    }

    public function deposit(Array $data)
    {
        return $this->accountModify([
            'id' => $data['destination'],
            'amount' => $data['amount'],
            'create' => true
        ], 'destination');
    }

    /**
     * Method responsible for effectively calling the service and making changes to account.
     * Used by deposit and withdraw
     *
     * @param $data
     * @param $jsonTarget
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory|null
     */
    private function accountModify($data, $jsonTarget)
    {
        try {
            $response = $this->repository->save($data);

            if (!$response) {
                return null;
            }

            return response($this->jsonFormatterForTest([
                $jsonTarget => [
                    'id' => strval($response['id']),
                    'balance' => $response['balance'],
                ]
            ]), ResponseAlias::HTTP_CREATED);
        } catch (Exception $exception) {
            return response()->json([
                'description' => 'Internal Server Error',
                'error' => $exception->getMessage()
            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
