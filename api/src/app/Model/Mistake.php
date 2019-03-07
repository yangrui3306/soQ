<?php

/**
 * @author : goodtimp
 * @time : 2019-3-1
*/

namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;


class Mistake extends NotORM
{

  protected function getTableName($id)
  {
    return 'mistake';
  }

  /**添加错题 ,返回添加题目的Id*/
  public function insertMistake($data)
  {
    $data["LikeNumber"] = 0;
    $data["CollectNumber"] = 0;
    $orm = $this->getORM();
    $orm->insert($data);
    return $orm->insert_id();
  }
  /**
     * 根据错题Id得到错题
		 * @param id 错题id
     */
  public function getMistakeById($id)
  {
    return $this->getORM()
      ->select('*')
      ->where('Id', $id)
      ->fetchAll();
  }

  /**
     * 根据题目Id查找所有错题
		 * @param qid 题目id
     */
  public function getMistakeByQId($qid,$start=0, $num = 0)
  {
    $re = $this->getORM()
      ->select('*')
      ->where('QuestionId', $qid)->order("CollectNumber,LikeNumber DESC");
    
    if ($num > 0) 
    {
      return $re->limit($start,$num)->fetchAll();
    }
    else return $re->fetchAll();
  }

  /**
			* 根据用户Id查找所有错题
			* @param uid 用户id
      */
  public function getMistakeByUId($uid,$start, $num = 0)
  {
    $re = $this->getORM()
      ->select('*')
      ->where('UserId', $uid)
      ->order("Id DESC");
    if ($num > 0) return $re->limit($start,$num)->fetchAll();
    return $re->fetchAll();
  }

  /**根据错题Id修改整理 
   * @param data {"Id".....}
   * @return 更新异常时返回false，数据无变化时返回0，成功更新返回1
  */
  public function updateMistake($data)
  {
    return $this->getORM()->where('Id',$data["Id"])->update($data);
  }

  /**
   * 点赞
   * @param 错题Id
   * @param f true为+1，false为-1
   * @return 返回受影响的行数
   */
  public function likeMistake($misid,$f=true)
  {
    return $this->getORM()->
    where('Id',$misid)
    ->update(array('LikeNumber'=>new \NotORM_Literal("LikeNumber ".($f?"+":"-")." 1")));
  }
  /**
   * 收藏
   * @param 错题Id
   * @param f true为+1，false为-1
   * @return 返回受影响的行数
   */
  public function collectionMistake($misid,$f=true)
  {
    return $this->getORM()->
    where('Id',$misid)
    ->update(array('CollectNumber'=>new \NotORM_Literal("CollectNumber ".($f?"+":"-")." 1")));
  }
}
