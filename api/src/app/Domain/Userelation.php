<?php
namespace App\Domain;

use App\Model\Userelation as Model;
use App\Model\User as UserModel;
class Userelation {

  public function addSid($id,$sid){
    $model = new Model();
    return $model->addSid($id,$sid);
  }
  public function getUserById($id)
  {
    $model = new Model();
    $um=new UserModel();
    $re=$model->getById($id);
    return $um->getUsersByIds($re["Sid"]); 
  }
}