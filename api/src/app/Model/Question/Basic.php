<?php

/**
 * @author : goodtimp
 * @time : 2019-3-3
*/

namespace App\Model\Question;

use PhalApi\Model\NotORMModel as NotORM;


class Basic extends NotORM
{
  /**---------------- m开头为返回数据库可操作类型-------------------- */

  protected function getTableName($id)
  {
    return 'question';
  }
  /**添加题目 */
  public function AddQuestion($q)
  {
    $orm = $this->getORM();
    $orm->insert($q);

    // 返回新增的ID（注意，这里不能使用连贯操作，因为要保持同一个ORM实例）
    return $orm->insert_id();
  }
  /**收藏 
      * @param f true为+1，false为-1
  */
  public function collectionQuestion($id, $f = true)
  {
    return $this->getORM()
      ->where('Id', $id)
      ->update(array('CollectNumber' => new \NotORM_Literal("CollectNumber " . ($f ? "+" : "-") . " 1")));
  }

  /**点赞
   *@param f true为+1，false为-1
   */
  public function likeQuestion($id, $f = true)
  {
    return $this->getORM()
      ->where('Id', $id)
      ->update(array('LikeNumber' => new \NotORM_Literal("LikeNumber " . ($f ? "+" : "-") . " 1")));
  }

  /**查找questionid */
  public function getQuestionById($id)
  {
    return $this->getORM()->where("Id", $id)->fetchOne();
  }

  /**将数组中的questionId替换成Question
   * @param data 数据[{"QuestionId"=>1}...]
   */

  public function replaceQuestionId(&$data)
  {
    for ($i = 0; $i < count($data); $i++) {
        if (array_key_exists("QuestionId", $data[$i])) {
            $data[$i]["Question"] = $this->getQuestionById($data[$i]["QuestionId"]);
          }
      }
    return $data;
  }
  

  /**根据关键字搜索题目 */
  public function getQuestionsByKeys($keys, $cid = 0, $start = 0, $num = 0)
  {
    $re = $this->getORM();
    if ($cid > 0)
      $re = $re->where("CategoryId", $cid);
    $re=$re->where("Text LIKE ?", $keys);
    $cnt=$re->count();
    $data= $re->limit($start, $num)
      ->order("Id DESC")->fetchAll();
    $data[count($data)]=$cnt;
    return $data;
  }
  /** 删除quesionts通过idarray */
  public function deleteQuestions($idarray)
  {
    return $this->getORM()->where("Id",$idarray)->delete();
  }
  /** 获取题目数量 */
  public function countQuestions($cid=0){
    $re=$this->getORM();
    if($cid>0) $re->where("CategoryId",$cid);
    return $re->count();
  }

	
	


	/* ------------  ipso  -------------- */

	/**
	 * 返回题库中题目的数量
	 */
	public function getCount(){
		$model = $this -> getORM();
		return $model -> count('Id');
	}

	public function deleteOne($id){
		$model = $this -> getORM();
		return $model -> where('Id', $id) -> delete();
	}

	public function deleteAll($data){
		$model = $this -> getORM();
		return $model -> where('Id', $data) -> delete();
	}

	public function update($Id, $data){
		$model = $this -> getORM();
		return $model -> where('Id', $Id) -> update($data);
	}

	/**
	 * 获取数值最高的前num条数据
	 * @param myType 收藏或者点赞的字段名
	 * @param num    要获取的数量
	 */
	public function getByMyType($myType = '', $num = 10){
		$model = $this -> getORM();
		if($myType == 'LikeNumber'){
			return $model -> order('LikeNumber desc') -> limit($num) -> fetchAll();
		}
		return $model -> order('CollectNumber desc') -> limit($num) -> fetchAll();
	}
}
