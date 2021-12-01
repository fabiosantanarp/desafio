<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonModel extends Model
{
    protected $table = 'Person';    

    public $timestamps = false;

    protected $primaryKey = 'idPerson';

    
}