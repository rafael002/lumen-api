<?php

namespace App\Repositories;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountRepositoryEloquentImpl implements AccountRepositoryInterface
{
    public function get($id)
    {
        $account = Account::find($id);
        return $account ? $account->toArray() : null;
    }

    public function save(Array $array)
    {
        /** @var Account $account */
        $account = Account::find($array['id']);

        if (!$account) {
            if (!$array['create']) {
                return null;
            }
            $account = new Account();
            $account->id = $array['id'];
            $account->balance = 0;
        }

        $account->balance += $array['amount'];
        $account->save();

        return $account->toArray();
    }
}
