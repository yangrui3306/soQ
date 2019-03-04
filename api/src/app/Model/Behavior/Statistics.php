<?php
/**
 * @author : goodtimp
 * @time : 2019-3-4
*/

namespace App\Model\Behavior;

use PhalApi\Model\NotORMModel as NotORM;


class Statistics extends NotORM
{

    protected function getTableName($id)
    {
        return 'behavior';
    }
    /**
     * 得到指定用户的最近几天内内或最近几道点赞题目统计返回相应数组
     * @param int $uid 用户Id
     * @param int $data 指定前几天(0为当天)，默认从创建开始
     * @param int $num 指定数量，默认为最大数量
     * @return 
     */
    public function getStatisticsLike($uid,$data=-1,$num=0)
    {
      $sbh=new Serach();
      $bhs=$sbh->mGetLikeByUserId($uid);
      return $bhs; 
    }
}
