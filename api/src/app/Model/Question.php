<?php

/**
 * @author : goodtimp
 * @time : 2019-3-1
*/

namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;


class Question extends NotORM
{

  protected function getTableName($id)
  {
    return 'question';
  }

  /**
     * 根据题目Id查找题目
     */
  public function getQuestionById($id)
  {
    $q=$this->getORM()
    ->select('*')
    ->where('Id', $id)
    ->fetchAll();
    return $q?$q[0]:null;
  }

  /**
     * 根据分类Id查找所有题目
     */
  public function getQuestionsByCategoryId($cid)
  {
    return $this->getORM()
      ->select('*')
      ->where('CategoryId', $cid)
      ->fetchAll();
  }

  
  /**
     * 根据分类Id和关键字匹配指定大于某数量的题目
		 * @param cid 分类id
		 * @param array keywords 传入的keyword的关键字优先级从高到低已排序,
		 * @param num 题目数量
     * @param questions 经过处理的数据库可直接操作的题目
     */
  public function GetQuestionsByKeyWord($cid, $keywords, $num = 3,$questions=null)
  {
    if($questions==null) $questions = $this->getORM();
    if ($cid != 0 && $cid != null) $questions = $questions->select('*')->where('CategoryId', $cid);
    
    if ($keywords != null) {
        $keyarr = $keywords;
        for ($i = 0; $i < count($keyarr); $i++) {
            $temp = $questions->where('KeyWords LIKE ?', '%' . $keyarr[$i] . '%');
            if (count($questions) < $num) break;
            $questions = $temp;
          }
      }
    return $questions->fetchAll();
  }

  /**
   * 查找用户没有收藏的题目
   * @return 数据库可操作类型
   */
  public function GetNotUserCollect($uid)
  {

  }
}
