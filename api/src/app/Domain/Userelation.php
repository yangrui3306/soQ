<?php
namespace App\Domain;

use App\Model\Userelation as Model;

class Userelation {

  public function addSid($id,$sid){
    $model = new Model();
    return $model->addSid($id,$sid);
  }
}