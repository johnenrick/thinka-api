<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Generic\GenericModel;

class NotificationStatementUpdate extends GenericModel
{
    use HasFactory;
    public function user(){
        return $this->belongsTo('App\Models\User');
    }
    public function relation(){
        return $this->belongsTo('App\Models\Relation');
    }
    public function statement(){
        return $this->belongsTo('App\Models\Statement');
    }
}
