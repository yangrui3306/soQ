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
  public function collectionQuestion($id,$f=true)
  {
    return $this->getORM()
    ->where('Id', $id)
    ->update(array('CollectNumber' => new \NotORM_Literal("CollectNumber ".($f?"+":"-")." 1")));
  }
  
  /**点赞
   *@param f true为+1，false为-1
   */
  public function likeQuestion($id,$f=true)
  {
    return $this->getORM()
    ->where('Id', $id)
    ->update(array('LikeNumber' => new \NotORM_Literal("LikeNumber ".($f?"+":"-")." 1")));
  }

  /**查找questionid */
  public function getQuestionById($id)
  {
    return $this->getORM()->where("Id",$id)->fetchOne();
  }

  /**将数组中的questionId替换成Question
   * @param data 数据[{"QuestionId"=>1}...]
   */

  public function replaceQuestionId(&$data)
  {
    for($i=0;$i<count($data);$i++)
    {
      if(array_key_exists("QuestionId",$data[$i]))
      {
        $data[$i]["Question"]=$this->getQuestionById($data[$i]["QuestionId"]);
      }
    }
    return $data;
  }

  /**根据关键字搜索题目 */
  public function getQuestionsByKeys($keys,$cid=0,$start=0,$num=0)
  {
    $re = $this->getORM();
    if($cid>0)
     $re=$re->where("CategoryId", $cid);
    
    return $re->where("Text LIKE ?", $keys)->limit($start,$num)
        ->order("Id DESC")->fetchAll();
    
  }
}
