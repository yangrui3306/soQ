<?php

/**
 * @author : goodtimp
 * @time : 2019-3-1
*/

namespace App\Model\Question;

use App\Model\Collection as ModelCollection;
use PhalApi\Model\NotORMModel as NotORM;
use PhalApi\Exception;
use App\Common\Match;

class Search extends NotORM
{
  /**---------------- m开头为返回数据库可操作类型-------------------- */

  protected function getTableName($id)
  {
    return 'question';
  }

  /**
   * 根据题目Id查找题目
    */
  public function getQuestionById($id)
  {
    $q = $this->getORM()
      ->select('*')
      ->where('Id', $id)
      ->fetchAll();
    return $q ? $q[0] : null;
  }
  /**
   * 查找除题目Id以外的其他题目
    */
    public function mGetNotQuestionById($id,$questions=null)
    {
      if ($questions == null) $questions = $this->getORM();
      return $questions
        ->select('*')
        ->where('Not Id', $id);      
    }
  /**
     * 根据分类Id查找所有题目
     * 
     */
  public function getQuestionsByCategoryId($cid)
  {
    return $this->getORM()
      ->select('*')
      ->where('CategoryId', $cid)
      ->fetchAll();
  }
  /**
     * 根据分类Id查找所有题目
     * @return 数据库可操作类型
     */
  public function mGetQuestionsByCategoryId($cid, $questions = null)
  {
    if ($questions == null) $questions = $this->getORM();
    return $questions
      ->select('*')
      ->where('CategoryId', $cid);
  }

/**
   * 查找用户没有收藏的题目
   * @return 数据库可操作类型
   */
  public function mGetNotUserCollect($uid, $questions = null)
  {
    $mcollection = new ModelCollection();
    if ($questions == null) $questions = $this->getORM();
    $idarr = $mcollection->getCollectionsByUserId($uid);
    return $questions->where('NOT Id',$idarr);
  }
  /**
     * 根据关键字匹配指定大于某数量的题目
		 * @param array keywords 传入的keyword的关键字优先级从高到低已排序,
		 * @param num 题目数量
     * @param questions 经过处理的数据库可直接操作的题目
     * @return 数据库可操作类型
     */
  public function mGetQuestionsByKeyWord($keywords, $num = 0, $questions = null)
  {
    if($num==null||$num<1) $num=3;
    if ($questions == null) $questions = $this->getORM();
    if ($keywords != null) {
      $keyarr = $keywords;
      for ($i = 0; $i < count($keyarr); $i++) {
        $temp = $questions->where('KeyWords LIKE ?', '%' . $keyarr[$i] . '%');
        if (count($questions) <= $num) break;
        $questions = $temp;
      }
    }
    return $questions;
  }

  /**
   * 题目判重
   * @param $q 题目
   * @param $id 题目Id
   * @param $leven 相似度大于等于该值将视为重复
   */
  public function checkDuplicate($q,$leven=0.95)
  {
    try{
      $questions=$this->mGetQuestionsByCategoryId($q["CategoryId"]);
     
      $questions=$questions->where('KeyWords', $q["KeyWords"]);
      
      $questions=Match::qLevenShtein($q,$questions->fetchALl(),1);
      $question=$questions[0];
    
      if(Match::levenShtein($q["Text"],$question["Text"])>=$leven) return $question;
    }
    catch(Exception $e)
    {
      return null;
    }
    return null;
  }
}
