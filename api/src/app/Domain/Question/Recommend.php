<?php
/**
 * @author : goodtimp
 * @time : 2018-3-2
 */

namespace App\Domain\Question;

use App\Model\Question\Search as ModelSearchQ;
use App\Common\ModelCommon as ModelCommon;
use App\Model\KeyWord as ModelKeyWord;
use App\Common\Tools as Tools;
use App\Common\Match as CommonMatch;
class Recommend
{
  /**
   * 通过题目Id，推荐相应题目,需修改用户Id
   * @param $id 题目Id
   * @param $num 需要推荐的数量，默认为3
   * @return 题目信息
   */
  public function recommend($id,$uid,$num=3)
  {
    $mquestion = new ModelSearchQ();

    $question = $mquestion->getQuestionById($id, $num);

    $questions = $mquestion->mGetQuestionsByCategoryId($question['CategoryId']); //查找分类相同的题目
    //需要cookie传入用户Id
    $questions = $mquestion->mGetNotUserCollect($uid); //查找用户没有收藏的题目

    $keys = explode(",", $question["KeyWords"]); //转数组
    $questions=$mquestion->mGetNotQuestionById($id,$questions);
    $questions = $mquestion->mGetQuestionsByKeyWord($keys, $num, $questions); //需修改
 
    return CommonMatch::qLevenShtein($question,$questions,$num);
  }
}
