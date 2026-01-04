<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $primaryKey = 'customerID';

    public $incrementing = true;   // if your PK is auto-increment int

    protected $keyType = 'int';

    protected $fillable = [
        'users_id',
        'name',
        'ic',
        'email',
        'phone_no  ',
        'address',
        'licenceNo',
    ];

    // If your table name is not "customers", uncomment this:
    // protected $table = 'customers';
}
