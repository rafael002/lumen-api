<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{

    /**
     * Not use eloquent timestamps
     * @var boolean
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'balance'
    ];
    /**
     * @var mixed
     */
    private $balance;
    /**
     * @var int|mixed
     */
    private $id;

}
