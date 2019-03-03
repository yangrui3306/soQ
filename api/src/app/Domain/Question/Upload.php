<?php
/**
 * @author : goodtimp
 * @time : 2018-3-3
 */

namespace App\Domain\Question;

use App\Model\Question\Search as ModelSearchQ;
use App\Common\ModelCommon as ModelCommon;
use App\Model\KeyWord as ModelKeyWord;
use App\Common\Tools as Tools;
use App\Common\Match as CommonMatch;
use App\Model\Question\Add as ModelAddQ;

/**
 * 上传题目相关操作
 */
class Upload
{
  /**
   * 通过题目Id，推荐相应题目,需修改用户Id
   * @param $q 题目{CategoryId,Content,Analysis,Type,KeyWords(文字形式.逗号隔开),SchoolId,Text}
   * @return 返回插入的Id
   */
  public function upQuestion($q)
  {
    $mquestion = new ModelAddQ();
    
    /*-------提取关键字---------*/
    if(!array_key_exists("Text",$q)||!$q["Text"]) $q["Text"]=$q["Content"];//保证Text必须有文字，否则输入原题的富文本
    $idarr = Tools::GetValueByKey(Tools::ExtractKeyWords($q["Text"]),"Id");//生成仅有Id字段的数组
    // 提取给出的关键字
    if (array_key_exists("KeyWords", $q)) {
      $idarr1 = Tools::GetValueByKey(Tools::ExtractKeyWords($q["KeyWords"]),"Id");
      $idarr = array_merge($idarr1, $idarr);
    }
    $q["KeyWords"]=implode(",", $idarr);

    return $mquestion->AddQuestion($q);
  }
}
