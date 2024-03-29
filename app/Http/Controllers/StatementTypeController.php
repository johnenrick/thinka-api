<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use App\Generic\GenericController;

class StatementTypeController extends GenericController
{
    function __construct(){
        $this->model = new App\Models\StatementType();
        $this->tableStructure = [
          'columns' => [
          ],
          'foreign_tables' => [
            // 'service_actions' => ["is_child" => true]
          ]
        ];
        $this->initGenericController();
    }
}
