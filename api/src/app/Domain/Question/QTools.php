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

class QTools
{
  /**合并Question中keywords与Keysweight字段，生成相应数组 
   * @param $keywors Quesiont表中KeyWords字段值
   * @param $keysweight Quesiont表中KeysWeight字段值
  */
  public static function mergeQuestionKeys($keywords,$keysweight)
  {
    try{
    
      $id = explode(",", $keywords); //转数组
      $weight =explode(",", $keysweight);
      
      $reslut=array();
      for($i=0;$i<count($id);$i++)
      {
        $reslut[$i]["Id"]=$id[$i];
        $reslut[$i]["Weight"]=$weight[$i];
      }
    }
    catch (Exception $e)
    {
      return [];
    }
    return $reslut;
  }

  /**剔除用户已经操作过的题目 */
  public static function deleteQuestionsForUser($uid,$qs=null)
  {
  
    $mquestion = new ModelSearchQ();
    if($qs==null) $qs=$mquestion->getAllQuestion();


    $questions=$mquestion->mGetNotUserCollect($uid,$qs);
    return $questions;
  }
}
