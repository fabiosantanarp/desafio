<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionModel extends Model
{
    protected $table = 'Transaction';    

    public $timestamps = false;

    protected $primaryKey = 'idTransaction';
    
}