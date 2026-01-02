<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $primaryKey = 'customerID';

    public $incrementing = true;   // if your PK is auto-increment int

    protected $keyType = 'int';

    protected $fillable = [
        'userID',
        'name',
        'ic',
        'email',
        'phone',
        'address',
        'licenceNo',
    ];

    // If your table name is not "customers", uncomment this:
    // protected $table = 'customers';
}
