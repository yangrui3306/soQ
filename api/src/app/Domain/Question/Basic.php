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
use PhalApi\Exception;

class Basic
{
  /**
   * 匹配题目
   * @param $q 必须含有Text键
   */
  public static function searchQuestion($q,$num=3)
  {
    $keys=Tools::ExtractKeyWords($q["Text"]);
    $mq=new ModelSearchQ();
    $qs=$mq->mGetQuestionsByKeyWord($keys,$num*2);
    return CommonMatch::qLevenShtein($q,$qs,$num);
  }
  /**推荐热门的题目 */
  public static function hotQuestion($num=5)
  {
    $mq=new ModelSearchQ();
    return $mq->getHotQuesion($num);
  }
  /**
   * 根据Id查找题目
   */
  public static function findQuestionById($id,$uid=0)
  {
    $q=QTools::getQuestionViewById($id,$uid);
  
    return $q;
  }
}
