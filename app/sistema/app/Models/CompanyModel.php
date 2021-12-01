<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyModel extends Model
{
    protected $table = 'Company';    

    public $timestamps = false;

    protected $primaryKey = 'idCompany';

    
}