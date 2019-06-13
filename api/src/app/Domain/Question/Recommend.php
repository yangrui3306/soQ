<?php
/**
 * @author : goodtimp
 * @time : 2018-3-2
 */

namespace App\Domain\Question;

use App\Model\Question\Search as ModelSearchQ;
use App\Domain\Behavior\Statistics;
use App\Common\Match as CommonMatch;
use App\Domain\Question\GeneratieTest;
class Recommend
{
  /**
   * 通过题目Id，推荐相应题目,需修改用户Id
   * @param $id 题目Id
   * @param $num 需要推荐的数量，默认为3
   * @return 题目信息 -2 原题不存在
   */
  public function recommendByQId($id, $uid=0, $num = 3)
  {
    $mquestion = new ModelSearchQ();

    $question = $mquestion->getQuestionById($id);
    if(!$question) return -2;
    
    $questions = $mquestion->mGetQuestionsByCategoryId($question['CategoryId']); //查找分类相同的题目
  
    if($uid!=0) $questions = QTools::deleteQuestionsForUser($uid); //剔除用户已经操作的题目
    $questions = $mquestion->mGetNotQuestionById($id, $questions);//去除本题
    
    //$keys = explode(",", $question["KeyWords"]); //转数组
    $keys=QTools::mergeQuestionKeys($question["KeyWords"],$question["KeysWeight"]);
  
    if(count($keys)) $questions=$mquestion->mGetByKeyWord($keys[0]["Id"],$questions); //得到有最重要的key的题目 大大剪枝
  
    $questions = CommonMatch::GetQuestionsByKeyWord($keys, $num, $questions,true,$question); //已修改
    return CommonMatch::qLevenShtein($question, $questions, $num);
  }

  /**
   * 根据用户Id，对其行为分析，推荐主页题目
   *  @param int $uid 用户Id
     * @param int $data 指定前几天(0为当天)，默认从创建开始
     * @param int $num 指定数量，默认为最大数量
     * @return 题目数组
   */

  public function recommendByUId($uid,$cid=0,$date=30,$num=10)
  {
    $ds=new Statistics();
    $keys=$ds->getStatisticsBehavior($uid,$cid,$date,$num);
    $qs=QTools::deleteQuestionsForUser($uid);//去除用户已经操作（收藏、错题整理等）部分
    
    $questions = CommonMatch::GetQuestionsByKeyWord($keys,$num,$qs,true); //关键字匹配相应题目
    return $questions;
  } 
}
