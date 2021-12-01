<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PersonModel;

class UserModel extends Model
{
    protected $table = 'User';    

    public $timestamps = false;

    protected $primaryKey = 'idUser';

    // get information about person.
    public function person() {

        return $this->hasOne(PersonModel::class, 'idUser');

    }

    // get information about company.
    public function company() {

        return $this->hasOne(CompanyModel::class, 'idUser');

    }    
    
}