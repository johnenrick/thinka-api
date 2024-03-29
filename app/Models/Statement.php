<?php

namespace App\Models;

use App\Generic\GenericModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statement extends GenericModel
{
    use HasFactory;
    public $validationRules = [
        'text' => 'required_without_all:id'
    ];
    protected $validationRuleNotRequired = ['user_id', 'scope_id', 'statement_certainty_id', 'scope', 'statement_certainty', 'synopsis', 'context_id', 'published_at'];
    public function logic_tree(){
        return $this->hasOne('App\Models\LogicTree');
    }
    public function statement_type(){
        return $this->belongsTo('App\Models\StatementType');
    }
    public function relation(){
        return $this->hasOne('App\Models\Relation');
    }
    public function relations(){
        return $this->hasMany('App\Models\Relation');
    }
    public function user_statement_logic_scores(){
        return $this->hasMany('App\Models\UserStatementLogicScore');
    }
}
