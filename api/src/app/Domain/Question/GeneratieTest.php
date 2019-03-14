<?php
/**
 * @author : goodtimp
 * @time : 2018-3-2
 */

namespace App\Domain\Question;

use App\Model\Question\Search as ModelSearchQ;
use App\Domain\Behavior\Statistics;
use App\Common\Cosines;
use App\Common\Tools;
use App\Common\Match;

class GeneratieTest
{

  /**
   * [{Id:1,Weight:1},{Id:2,Weight:2}]=>{Keys:[1,2],Weight:[1,2]}
   */
  private function merageArray($arr)
  {
    $re = array(
      "Keys" => [],
      "Weight" => []
    );
    for ($i = 0; $i < count($arr); $i++) {
        $re["Keys"][$i] = $arr[$i]["Id"];
        $re["Weight"][$i] = $arr[$i]["Weight"];
      }
    return $re;
  }
  /**
     * 根据关键字匹配指定大于某数量的题目（优先匹配关键字最多的 需修改按大小匹配！！）
		 * @param array keywords [{"Id":"2","Weight":"3"}...} 降序
		 * @param num 题目数量
     * @param questions 经过处理的数据库可直接操作的题目
     * @return 数据库可操作类型
     */
  public function GetQuestionsByKeyWord($keywords, $num = 0, $questions = null)
  {
    if($questions==null) return [];
    if ($num == null || $num < 1) $num = 3;
    if ($keywords) {
      $keyarr = $this->merageArray($keywords);
      $questions=$questions->fetchAll(); //抓取所有
      for ($i = 0; $i < count($questions); $i++) {
          $temp = array(
            "Keys" => explode(",", $questions[$i]["KeyWords"]),
            "Weight" => explode(",", $questions[$i]["KeysWeight"])
          );
          
          $cos = new Cosines();
          $questions[$i]["Similarity"]=$cos->run($keyarr, $temp);
        }
      Tools::SortByKey($questions,"Similarity",false);
        return array_slice($questions,0,$num);//获取前n个记录
    }
  }


  /**
 * 得到测试题目
 * @param uid 用户id
 * @param cid 分类id
 * @param date 时间 天数
 * @param num 获取数量 
 */
  public function getTest($uid, $cid = 0, $date = 30, $num = 10)
  {
    $ds = new Statistics();
    $keys = $ds->getStatisticsBehavior($uid, $cid, $date); //统计最近用户行为题目的keys

    $mquestion = new ModelSearchQ();
    $qs = QTools::deleteQuestionsForUser($uid); //去除用户已经操作（收藏、错题整理等）部分
    $questions = Match::GetQuestionsByKeyWord($keys, $num, $qs); //关键字匹配相应题目
    return $questions;
  }
}
