<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    const STATUS_ACTIVE = 1;
    const NOT_DELETED   = 0;

}
