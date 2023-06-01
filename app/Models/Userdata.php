<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Userdata extends Model
{
    use HasFactory;
    protected $table = 'UserData';

    public $timestamps = false;

    //大量データ移行するので、これ書いとかないと色々怒られる
    protected $fillable = [
        'UID',
        'IDMNo',
        'FullName',
        'CodeNo',
        'Section',
        'SectionId',
        'Licences',
        'OfficialPosition',
        'WOFlag',
        'DAFlag',
        'NUFlag',
        'DRFlag',
        'COFlag',
        'CBFlag',
        'BOFlag',
        'SOFlag',
        'RNFlag',
        'ADFlag',
        'SCHFlag',
        'PlanSectionId',
        'PlanDate',
        'RetireDate',
        'KaiFlag',
        'GaiFlag'
    ];
}
