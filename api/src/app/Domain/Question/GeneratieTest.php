<?php
/**
 * @author : goodtimp
 * @time : 2018-3-2
 */

namespace App\Domain\Question;

use App\Model\Question\Search as ModelSearchQ;
use App\Domain\Behavior\Statistics;

/**
 * 得到测试题目
 * @param uid 用户id
 * @param cid 分类id
 * @param date 时间 天数
 * @param num 获取数量 
 */
class GeneratieTest
{
  public function getTest($uid,$date=30,$num=10,$cid=0)
  {
    $ds=new Statistics();
    $keys=$ds->getStatisticsBehavior($uid,$date,$num);

    $mquestion = new ModelSearchQ();
    $qs=QTools::deleteQuestionsForUser($uid);//去除用户已经操作（收藏、错题整理等）部分
    $qs=$qs->where("CategoryId",$cid);//得到相同类型的题目
    $questions = $mquestion->mGetQuestionsByKeyWord($keys,$num,$qs); //关键字匹配相应题目
    return $questions;
  }
 
}
