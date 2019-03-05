<?php
/**
 * @author : goodtimp
 * @time : 2019-3-4
*/

namespace App\Model\Behavior;

use PhalApi\Model\NotORMModel as NotORM;
use PhalApi\Exception;


class Search extends NotORM
{

  protected function getTableName($id)
  {
    return 'behavior';
  }

  /** 添加收藏行为
     * @param 
    */
  public function addBehavior($be)
  {
    $be["Date"] = date('Y-m-d h:i:s', time());
    return $this->insert($be);
  }
  /**
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
}
