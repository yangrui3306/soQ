<?php

/**
 * @author : goodtimp
 * @time : 2019-3-1
*/

namespace App\Model\Examples;

use PhalApi\Model\NotORMModel as NotORM;


class Mistake extends NotORM {

    protected function getTableName($id) {
        return 'Mistake';
    }
    /**
     * 根据错题Id得到错题
     */
    public function getMistakeById($id) {
      return $this->getORM()
          ->select('*')
          ->where('Id',$id)
          ->fetchAll();
    }
    /**
     * 根据题目Id查找所有错题
     */
    public function getMistakeByQId($qid) {
        return $this->getORM()
            ->select('*')
            ->where('QuestionId',$qid)
            ->fetchAll();
    }
     /**
     * 根据用户Id查找所有错题
     */
    public function getMistakeByUId($uid) {
      return $this->getORM()
          ->select('*')
          ->where('UserId',$uid)
          ->fetchAll();
  }
}
