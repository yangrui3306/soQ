<?php
/**
 * @author : goodtimp
 * @time : 2019-3-4
*/

namespace App\Domain\Behavior;

use App\Model\Behavior\Search as ModelSearch;
use App\Model\Question\Search as ModelSearchQ;
use App\Common\Tools;
use App\Domain\Question\QTools;

class Statistics
{
  const mistakeWeight=2,collectionWeight=1.5,likeWeight=1.2,searchWeight=1;
  /**
     * 得到指定用户的最近几天内行为统计返回相应数组
     * @param int $uid 用户Id
     * @param int $data 指定前几天(0为当天)，默认从创建开始
     * @param int $num 指定数量，默认为最大数量
     * @return 用户关键字数组
     */
    public function getStatisticsBehavior($uid, $data = -1, $num = 0)
    {
      $sbh = new ModelSearch();
      $mq = new ModelSearchQ();
      
      $cs=$this->getStatisticsCollection($uid,$data,$num);
      $ls=$this->getStatisticsLike($uid,$data,$num);
      $ms=$this->getStatisticsMistake($uid,$data,$num);
      $ss=$this->getStatisticsSearch($uid,$data,$num);
      $keys = array_merge($cs,$ls,$ms,$ss);
      Tools::mergeKeyWeight($keys);
      Tools::SortByKey($keys,"Weight",false);
      return $keys;
    }


  /**
     * 得到指定用户的最近几天内内或最近几道收藏题目统计返回相应数组
     * @param int $uid 用户Id
     * @param int $data 指定前几天(0为当天)，默认从创建开始
     * @param int $num 指定数量，默认为最大数量
     * @return 用户关键字数组
     */
  public function getStatisticsCollection($uid, $data = -1, $num = 0)
  {
    $sbh = new ModelSearch();
    $mq = new ModelSearchQ();

    $bhs = $sbh->mGetCollectionsByUserId($uid, $data, $num)->where("NOT QuestionId", array(null, 0)); //剔除未上传题库的题目
    $idarr = Tools::GetValueByKey($bhs, "QuestionId");
    $qs = $mq->getQuestionsByIdarr($idarr);
   
    $keys=$this->getKeyWrodsByQs($qs);
    return $this->AddWeightForKeys($keys,Statistics::collectionWeight);
  }

/**
     * 得到指定用户的最近几天内内或最近几道搜索题目统计返回相应数组
     * @param int $uid 用户Id
     * @param int $data 指定前几天(0为当天)，默认从创建开始
     * @param int $num 指定数量，默认为最大数量
     * @return 
     */
  public function getStatisticsSearch($uid, $data = -1, $num = 0)
  {
    $sbh = new ModelSearch();
    $mq = new ModelSearchQ();

    $bhs = $sbh->mGetSearchByUserId($uid, $data, $num)->where("NOT QuestionId", array(null, 0)); //剔除未上传题库的题目
    $idarr = Tools::GetValueByKey($bhs, "QuestionId");
    $qs = $mq->getQuestionsByIdarr($idarr);
    $keys=$this->getKeyWrodsByQs($qs);
    return $this->AddWeightForKeys($keys,Statistics::searchWeight);
  }
  /**
     * 得到指定用户的最近几天内内或最近几道错题整理的题目统计返回相应数组
     * @param int $uid 用户Id
     * @param int $data 指定前几天(0为当天)，默认从创建开始
     * @param int $num 指定数量，默认为最大数量
     * @return 
     */
  public function getStatisticsMistake($uid, $data = -1, $num = 0)
  {
    $sbh = new ModelSearch();
    $mq = new ModelSearchQ();

    $bhs = $sbh->mGetMistakeByUserId($uid, $data, $num)->where("NOT QuestionId", array(null, 0)); //剔除未上传题库的题目
    $idarr = Tools::GetValueByKey($bhs, "QuestionId");
    $qs = $mq->getQuestionsByIdarr($idarr);
    $keys=$this->getKeyWrodsByQs($qs);
    return $this->AddWeightForKeys($keys,Statistics::mistakeWeight);
  }


  /**
     * 得到指定用户的最近几天内内或最近几道点赞题目统计返回相应数组
     * @param int $uid 用户Id
     * @param int $data 指定前几天(0为当天)，默认从创建开始
     * @param int $num 指定数量，默认为最大数量
     * @return 
     */
  public function getStatisticsLike($uid, $data = -1, $num = 0)
  {
    $sbh = new ModelSearch();
    $mq = new ModelSearchQ();

    $bhs = $sbh->mGetLikeByUserId($uid, $data, $num)->where("NOT QuestionId", array(null, 0)); //剔除未上传题库的题目
    $idarr = Tools::GetValueByKey($bhs, "QuestionId");
    $qs = $mq->getQuestionsByIdarr($idarr);
    $keys=$this->getKeyWrodsByQs($qs);
    return $this->AddWeightForKeys($keys,Statistics::likeWeight);
  }

/**
     * 统计 题目数组 中的关键字,并生成相应降序的关键字数组
     * @param arry $qs 题目数组
     * @return arry [{"Id":1,"Weight":1},....]
     */
  public function getKeyWrodsByQs($qs)
  {
    $key = array();
    foreach ($qs as $q) {
      $idarr = explode(",", $q["KeyWords"]);

      $weightarr = explode(",", $q["KeysWeight"]);
      $key1 = array();
      for ($i = 0; $i < count($idarr); $i++) {
        $key1[$i]["Id"] = $idarr[$i];
        $key1[$i]["Weight"] = $weightarr[$i];
      }
      $key = array_merge($key, $key1);
    }
    Tools::mergeKeyWeight($key); //合并KeyWeight
    Tools::SortByKey($key, "Weight", false);
    return $key;
  }

  /**添加权重到关键字数组 */
  private function AddWeightForKeys(&$keys,$weight)
  {
    for($i=0;$i<count($keys);$i++)
    {
      $keys[$i]["Weight"]=$weight*$keys[$i]["Weight"];
    }
    return $keys;
  }
}
