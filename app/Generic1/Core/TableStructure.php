<?php

namespace App\Generic\Core;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App;
class TableStructure
{
  public $model;
  public $tableStructure;
  function __construct($tableStructure, $model){
    $this->model = $model;
    $this->tableStructure = $tableStructure;
    $this->initStructure();
  }
  public function getStructure(){
    return $this->tableStructure;
  }
  public function initStructure(){
    $tableStructure = $this->tableStructure;
    $tableStructure = $this->setColumnsDefault($tableStructure, $this->model);
    $tableStructure['table_name'] = $this->model->getTable();
    $tableStructure['true_table'] = $tableStructure['table_name'];
    $tableStructure['validation_required'] = true;
    $tableStructure = $this->initForeignTableStructure($tableStructure);
    $this->tableStructure = $tableStructure;
  }
  public function initForeignTableStructure($tableStructure, $treeMap = null){
    if(isset($tableStructure['foreign_tables']) && count($tableStructure['foreign_tables'])){
      $treeMap = $treeMap == null ? $tableStructure['table_name'] : $treeMap.'.'.$treeMap;
      foreach($tableStructure['foreign_tables'] as $foreignTableName => $foreignTableStructure){
        $trueTable = $foreignTableName;
        if(isset($foreignTableStructure['true_table'])){
          $trueTable = strtolower($foreignTableStructure['true_table']);
        }
        $foreignTableStructure['true_table'] = str_plural($trueTable);
        $foreignTableModelClassName = "App\\Models\\".(strToPascal(str_singular($trueTable)));
        $foreignTableStructure = $this->setColumnsDefault($foreignTableStructure, new $foreignTableModelClassName());
        $foreignTableStructure['model_name'] = $foreignTableModelClassName;
        $foreignTableStructure['table_name'] = str_plural($foreignTableName);
        $foreignTableStructure['multiple'] = str_plural($foreignTableName) == $foreignTableName ? true : false;
        $foreignTableStructure['tree_map'] = $treeMap;
        $foreignTableStructure[''] =
        $tableStructure['foreign_tables'][$foreignTableName] = $this->initForeignTableStructure($foreignTableStructure, $treeMap);
        if(!isset($tableStructure['foreign_tables'][$foreignTableName]['is_child'])){
          $tableStructure['foreign_tables'][$foreignTableName]['is_child'] = false;
          if(isset($tableStructure['foreign_tables'][$foreignTableName]['columns'][   str_singular($tableStructure['table_name'])."_id"   ])){ // check if foreign table is child or parent
            $tableStructure['foreign_tables'][$foreignTableName]['is_child'] = true;
          }
        }
        $tableStructure['foreign_tables'][$foreignTableName]['validation_required'] = isset($foreignTableStructure['validation_required']) ? $foreignTableStructure['validation_required'] : $tableStructure['foreign_tables'][$foreignTableName]['is_child'];
      }
    }else{
      $tableStructure['foreign_tables'] = [];
    }
    return $tableStructure;
  }
  public function setColumnsDefault($tableStructure, $model){
    $tableStructure['columns'] = isset($tableStructure['columns']) ? $tableStructure['columns'] : [];
    $tableColumns = $model->getTableColumns();
    $formulatedTableColumns = $model->getFormulatedColumn();
    $tableValidationRule = $model->getValidationRule();

    for($x = 1; $x < 4; $x++){ // remove the created, updated, deleted ats columns
      if($tableColumns == 'created_at' || $tableColumns == 'updated_at' || $tableColumns == 'deleted_at')
        unset($tableColumns[count($tableColumns) - 1]);
    }

    for($columnIndex = 0; $columnIndex < count($tableColumns); $columnIndex++){
      $columnName = $tableColumns[$columnIndex];
      if(!isset($tableStructure['columns'][$columnName])){ // set undefined column in table struture
        $tableStructure['columns'][$columnName] = [];
      }
      if(!isset($tableStructure['columns'][$columnName]['validation']) && isset($tableStructure['columns'][$columnName]) && isset($tableValidationRule[$columnName])){
        $tableStructure['columns'][$columnName]['validation'] = $tableValidationRule[$columnName];
      }else{ // merge controller level validation
        // TODO
      }
    }
    foreach($formulatedTableColumns as $columnName => $formula){
      if(!isset($tableStructure['columns'][$columnName])){ // set undefined column in table struture
        $tableStructure['columns'][$columnName] = [];
      }
      if(!isset($tableStructure['columns'][$columnName]['formula'])){
        $tableStructure['columns'][$columnName]['formula'] = $formula;
      }
    }
    $tableStructure['columns']['created_at'] = [
      'validation' => ''
    ];
    $tableStructure['columns']['updated_at'] = [];
    $tableStructure['columns']['deleted_at'] = [];
    return $tableStructure;
  }
}
