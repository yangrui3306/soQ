<?php
/**
 * @author : goodtimp
 * @time : 2019-3-4
*/

namespace App\Domain\Behavior;
use App\Model\Serach as ModelSearch;

class Statistics 
{

    /**
     * 得到指定用户的最近几天内内或最近几道点赞题目统计返回相应数组
     * @param int $uid 用户Id
     * @param int $data 指定前几天(0为当天)，默认从创建开始
     * @param int $num 指定数量，默认为最大数量
     * @return 
     */
    public function getStatisticsLike($uid,$data=-1,$num=0)
    {
      $sbh=new ModelSearch();
      $bhs=$sbh->mGetLikeByUserId($uid,$data,$num)->where("NOT QuestionId",array(null,0));//剔除未上传题库的题目
      
      return $bhs; 
    }

    /**
     * 统计题目数组中的关键字
     */
}
