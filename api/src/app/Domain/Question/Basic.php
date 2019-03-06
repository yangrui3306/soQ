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
   * 查找题目
   * @param $q 必须含有Text键
   */
  public static function searchQuestion($q,$num=3)
  {
    $keys=Tools::ExtractKeyWords($q["Text"]);
    $mq=new ModelSearchQ();
    $qs=$mq->mGetQuestionsByKeyWord($keys,$num*2);
    return CommonMatch::qLevenShtein($q,$qs,$num);
  }
}
