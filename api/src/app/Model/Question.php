<?php

/**
 * @author : goodtimp
 * @time : 2019-3-1
*/

namespace App\Model\Examples;

use PhalApi\Model\NotORMModel as NotORM;


class Question extends NotORM {

    protected function getTableName($id) {
        return 'question';
    }
    /**
     * 根据题目Id查找题目
     */
    public function getQuestionById($id) {
      return $this->getORM()
          ->select('*')
          ->where('Id',$id)
          ->fetchAll();
  }
    /**
     * 根据分类Id查找所有题目
     */
    public function getQuestionsByCategoryId($cid) {
        return $this->getORM()
            ->select('*')
            ->where('CategoryId',$cid)
            ->fetchAll();
    }
    /**
     * 根据分类Id和关键字匹配指定大于某数量的题目
     * 注：传入的keyword的关键字优先级从高到低已排序
     */
    public function GetQuestionsByKeyWord($cid,$keywords,$num=3)
    {
      $questions=$this->getORM();
      if($cid!=0&&$cid!=null) $questions=$questions->select('*')->where('CategoryId',$cid);
      if($keywords!=null)
      {
        $keyarr=implode(',',$keywords);
        for($i=0;$i<count($keyarr);$i++)
        {
          $temp=$questions->where('KeyWords LIKE ?','%'.$keyarr[$i].'%');
          if(count($questions)<$num) break;
          $questions=$temp;
        }
      }
      return $questions->fetchAll();
    }
}
