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
use App\Model\Question\Basic as ModelQBasic;
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
   
    $qs=CommonMatch::GetQuestionsByKeyWord($keys,$num*2);
 
    return CommonMatch::qLevenShtein($q,$qs,$num);
  }

  /**
   * 根据text模糊匹配题目
   */
  public static function matchQuestion($text,$uid,$num=4)
  {
   
    $keys=Tools::ExtractKeyWords($text);
    $qs=QTools::deleteQuestionsForUser($uid);//去除用户已经操作（收藏、错题整理等）部分

    $qs=CommonMatch::GetQuestionsByKeyWord($keys,$num*2,$qs);
    $q=array("Text"=>$text);
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
  /**根据关键字进行匹配 */
  public function getByKeys($keys,$cid=0,$page=0,$num=0)
  {
    $min=Tools::getPageRange($page,$num);
    $qm=new ModelQBasic();
    $qms=new ModelSearchQ();
    if($keys=="") 
    {
      $re=$qms->getAllQuestion($min,$num);
      $re[count($re)]=$qms->getQuestionsCount($cid);
      return $re;
    }
    $keys=CommonMatch::AllWordMatch($keys);
 
  
    return $qm->getQuestionsByKeys($keys,$cid,$min,$num);
  }
  /**
   * 删除题目
   */
  public function deleteQuestions($idarray)
  {
    $qm=new ModelQBasic();
    return $qm->deleteQuestions($idarray);
  }
  /**
   * 获取题目数量
   */
  
  public function countQuestions($cid=0)
  {
    $qm=new ModelQBasic();
    return $qm->countQuestions($cid);
  }




	/* --------------   author ipso   ----------------- */
	
	/**
	 * 获取题库中的题目数量
	 */
	public function getCount(){
		$model = new ModelQBasic();
		$count = $model -> getCount();
		return $count;
	}

	/**
	 * 删除题目
	 */
	public function delete($data){
		$model = new ModelQBasic();
		$Ids = explode(',', $data);
		$count = count($Ids);
		for($i = 0; $i < $count; $i++){
			$res = $model -> deleteOne($Ids[$i]);
			if(!$res){
				return 1;
			}
			return 0;
		}
	}

	/**
	 * 更新题目
	 */
	public function updateQuestion($Id, $data){
		$model = new ModelQBasic();
		$sql = $model -> update($Id, $data);
		if(!$sql){
			return 1;
		}
		return 0;
	}

	/**
	 * 获取题目收藏数前10
	 */
	public function getCollection(){
		$model = new ModelQBasic();
		$myType = 'CollectNumber';
		$list = $model -> getByMyType($myType, 10);
		if(!$list){
			return 1;
		}
		return $list;
	}

	/**
	 * 获取题目热度前10 
	 * (获取收藏数前100的题目，并求每道题目的收藏数和点赞数之和，取前10作为热度前十题目)
	 */
	public function getLike(){
		$model = new ModelQBasic();
		$myType = 'CollectNumber';
		$list = $model -> getByMyType($myType, 100);
		$count = count($list);
		for($i = 0; $i < $count; $i++){
			$list[$i]['sum'] = $list[$i]['CollectNumber'] + $list[$i]['LikeNumber'];
		}
		$newList = $this -> getMaximum($list);
		return $newList;
	}

	/**
	 * 快速排序获取前十
	 */
	private function getMaximum($data){
		$length = count($data);
		$newArr = '';
		// 对数组按$data[key]['sum']排序
		for($i = 0; $i < $length; ++$i){
			$k = $i;
			for($j = $i + 1; $j < $length; ++$j){
				if($data[$j]['sum'] > $data[$k]['sum']){
					$k = $j;
				}
			}
			if($k != $i){
				$arr = $data[$i];
				$data[$i] = $data[$k];
				$data[$k] = $arr;
			}
		}

		// 取出排序好数组的前10位放入数组newArr中
		for($i = 0; $i < 10; $i++){
			$newArr[$i] = $data[$i];
		}

		return $newArr;
	}
}
