<?php
/**
 * @author : goodtimp
 * @time : 2019-3-4
*/

namespace App\Model\Behavior;

use PhalApi\Model\NotORMModel as NotORM;
use PhalApi\Exception;


class Basic extends NotORM
{

  protected function getTableName($id)
  {
    return 'behavior';
  }

  /** 添加行为
     * @param 
    */
  public function addBehavior($be)
  {
    $be["Date"] = date('Y-m-d h:i:s', time());
    return $this->insert($be);
  }
  /** 需更改，QuestionId可能为空
   * 更新停留时间,相加
   * @param $be {"UsreId","QuestionId","StandTime"}
   * @return 返回受影响的行数
   */
  public function updateStandTime($be)
  {
    try {
      $bes = $this->getORM()->where("UserId", $be["UserId"])->where("UserId", $be["UserId"]);
      $arr = $bes->fetchAll();
      for ($i = 0; $i < count($arr); $i++) {
          $arr[$i]["StandTime"] += $be["StandTime"];
          $bes->update($arr[$i]);
        }
    } catch (Exception $e) {
      return 0;
    }
    return count($arr);
  }
  /** 判断用户是否已经点赞
   * @return bool true:已经点赞 false:没有点赞 
   */
  public function judgeUserLike($uid,$mid=0,$qid=0)
  {
    $re=$this->getORM()->where("UserId",$uid);
  
    if($mid>0)
    {
      $re=$re->where("MistakeId",$mid);
      //return $re;
    }
    if($qid>0) $re=$re->where("QuestionId",$qid);
 
    return $re->count()>0?true:false;
  }
}
