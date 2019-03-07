<?php
namespace App\Domain;
use App\Model\Like as ModelLike;
use App\Model\Mistake as ModelMistake;
use App\Model\Behavior\Basic as ModelBehavior;
use App\Model\Question\Basic as ModelQuestion;
class Like
{
  /**添加点赞 */
	public function add($data,$StandTime)
	{
		$lm=new ModelLike();
		$id=$lm->insertOne($data);//添加收藏记录
		if(!($id>0)) return 0;

		if($data["MistakeId"]>0)// 增加相应点赞数量
		{
			$mm=new ModelMistake();
			$mm->likeMistake($data["MistakeId"]);
		}
		else if($data["QuestionId"]>0)
		{
			$mm=new ModelQuestion;
			$mm->likeQuestion($data["QuestionId"]);
		}

		$bm=new ModelBehavior();//添加收藏行为
		$bd=array(
			"UserId"=>$data["UserId"],
			"Type"=>2,
			"QuestionId"=>$data["QuestionId"],
			"MistakeId"=>$data["MistakeId"],
			"StandTime"=>$StandTime
		);
		$bm->addBehavior($bd);

		return $id;
	}
}