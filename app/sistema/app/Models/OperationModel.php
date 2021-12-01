<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperationModel extends Model
{
    protected $table = 'Operation';    

    public $timestamps = false;

    protected $primaryKey = 'idOperation';
    
}