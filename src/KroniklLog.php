<?php

namespace Dual\Kronikl;

use Illuminate\Database\Eloquent\Model;

class KroniklLog extends Model
{
    protected $primaryKey = "id";
    protected $fillable = [
        "user_id",
        "model_name",
        "model_id",
        "action",
        "record"
    ];
}
