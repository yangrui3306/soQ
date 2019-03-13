<?php
namespace App\Domain;

use App\Model\Like as ModelLike;
use App\Model\Mistake as ModelMistake;
use App\Model\Behavior\Basic as ModelBehavior;
use App\Model\Question\Basic as ModelQuestion;

use App\Model\Interest as ModelInterest;

class Like
{
	/**添加点赞 */
	public function add($data, $StandTime)
	{
		$lm = new ModelLike();
		$id = $lm->insertOne($data); //添加点赞记录

		if ($id == -1) {
				return $this->delete($data);
			}
		if ($id == 0) return $id;

		if ($data["MistakeId"] > 0) // 增加相应点赞数量
			{
				$mm = new ModelMistake();
				$mm->likeMistake($data["MistakeId"]);
			} else if ($data["QuestionId"] > 0) {
				$mm = new ModelQuestion;
				$mm->likeQuestion($data["QuestionId"]);
			}


		$bm = new ModelBehavior(); //添加点赞行为
		$bd = array(
			"UserId" => $data["UserId"],
			"Type" => 2,
			"QuestionId" => $data["QuestionId"],
			"MistakeId" => $data["MistakeId"],
			"StandTime" => $StandTime
		);
		$bm->addBehavior($bd);


		//添加感兴趣度
		if ($data["QuestionId"] > 0) {
				$qd = array(
					"UserId" => $data["UserId"],
					"Behavior" => "Like",
					"QuestionId" => $data["QuestionId"],
				);
				$im = new ModelInterest();
				$im->addInterest($qd);
			}


		return $id;
	}



	/**删除点赞 */
	public function delete($data)
	{
		$lm = new ModelLike();
		$data = $lm->deleteOne($data["Id"]); //删除点赞表内数据,得到数据
		if ($data == 0) return 0;

		if ($data["MistakeId"] > 0) // 减少相应点赞数量
			{
				$mm = new ModelMistake();
				$mm->likeMistake($data["MistakeId"], false);
			} else if ($data["QuestionId"] > 0) {
				$mm = new ModelQuestion;
				$mm->likeQuestion($data["QuestionId"], false);

				//减少某题兴趣度
				$qd = array(
					"UserId" => $data["UserId"],
					"Behavior" => "Like",
					"QuestionId" => $data["QuestionId"],
				);
				$im = new ModelInterest();
				$im->reduceInterest($qd);
			}
		return -1;
	}
}

