<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserdataSqlsrv extends Model
{
    use HasFactory;

    protected $table = 'UserData';

    //sqlsrv用のmodel。データ取り込みの際に使う。
    protected $connection = 'sqlsrv';
}
