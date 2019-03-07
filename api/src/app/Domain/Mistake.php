<?php
namespace App\Domain;

use App\Model\Behavior\Basic as ModelBehavior;
use App\Model\Mistake as ModelMistake;
use App\Model\MistakeCategory as ModelMCategory;
class Mistake {
  
  /**得到用户所有分类信息 */
  public function getCategory($uid)
  {
    $mmc=new ModelMCategory();
    return $mmc->getCategoryByUserId($uid);
  }
  /**添加mistake，并添加相应的behavior数据 */
  public function addMistake($data,$standtime=0){
    $mm=new ModelMistake();
    $id=$mm->insertMistake($data);
    if($id>0)
    {
      $mb=new ModelBehavior();
      $be=array("UserId"=>$data["UserId"],
      "Type"=>3,
      "QuestionId"=>$data["QuestionId"],
      "MistakeId"=>$id,
      "StandTime"=>$standtime);

      $mb->addBehavior($be);
    }
    return $id;
  }
  /**更新数据 */
  public function updateMistake($data)
  {
    $mm=new ModelMistake();
    return $mm->updateMistake($data);
  }


  /**根据题目Id查找错题（按热度排序）
   * @param num 数量
   */
  public function getMistakeByQuestionId($qid,$num=5)
  {
    $mm=new ModelMistake();
    return $mm->getMistakeByQId($qid,$num);
  }
  /**根据用户Id查找所有错题（按时间排序）
   * @param num 数量
   */
  public function getMistakeByUserId($uid,$num=5)
  {
    $mm=new ModelMistake();
    return $mm->getMistakeByUId($uid,$num);
  }
/**点赞
 * @param data {"MistakeId","UserId","QuestionId"}
 * @return 0失败 1成功
 */

  public function addLike($data,$standtime=0)
  {
    $mm=new ModelMistake();
    $bm=new ModelBehavior();
   
   // return $bm->judgeUserLike($data["UserId"],$data["MistakeId"],$data["QuestionId"]);
    if($bm->judgeUserLike($data["UserId"],$data["MistakeId"],$data["QuestionId"])) return 0;

    if($mm->likeMistake($data["MistakeId"])>0)
    {
      $mb=new ModelBehavior();
      $be=array("UserId"=>$data["UserId"],
      "Type"=>2,
      "QuestionId"=>$data["QuestionId"],
      "MistakeId"=>$data["MistakeId"],
      "StandTime"=>$standtime
    );
      $mb->addBehavior($be);
      return 1;
    }
    return 0;
     
  }
}