<?php
namespace App\Domain;
use App\Model\Collection as ModelCollection;
use App\Model\Mistake as ModelMistake;
use App\Model\Behavior\Basic as ModelBehavior;
use App\Model\Question\Basic as ModelQuestion;
use App\Common\Tools;
use App\Model\Interest as ModelInterest;

class Collection
{
	public function add($data,$StandTime)
	{
		$cm=new ModelCollection();
		$id=$cm->insertOne($data);//添加收藏记录

	
		if($id==-1)
			{
				return $this->delete($data);
			}
		if($id==0) return $id;

		if($data["MistakeId"]>0)// 增加相应点赞数量
		{
			$mm=new ModelMistake();
			$mm->collectionMistake($data["MistakeId"]);
		}
		else if($data["QuestionId"]>0)
		{
			$mm=new ModelQuestion;
			$mm->collectionQuestion($data["QuestionId"]);
		}
	


		$bm=new ModelBehavior();//添加收藏行为
		$bd=array(
			"UserId"=>$data["UserId"],
			"Type"=>3,
			"QuestionId"=>$data["QuestionId"],
			"MistakeId"=>$data["MistakeId"],
			"StandTime"=>$StandTime
		);
		$bm->addBehavior($bd);


		//添加感兴趣度
    if($data["QuestionId"]>0)
		{
			$qd=array(
				"UserId"=>$data["UserId"],
				"Behavior"=>"Collection", 
				"QuestionId"=>$data["QuestionId"],
			);
			$im=new ModelInterest();
			$im->addInterest($qd);
		}


		return $id;
	}

	/**删除收藏 */
	public function delete($data){
		$cm=new ModelCollection();
		$data=$cm->deleteOne($data["Id"]);//删除收藏表内数据,得到数据
		if($data==0) return 0;
		if($data["MistakeId"]>0)// 增加相应点赞数量
		{
			$mm=new ModelMistake();
			$mm->collectionMistake($data["MistakeId"],false);
		}
		else if($data["QuestionId"]>0)
		{
			$qm=new ModelQuestion;
			$qm->collectionQuestion($data["QuestionId"],false);

			//减少某题兴趣度
			$qd=array(
				"UserId"=>$data["UserId"],
				"Behavior"=>"Collection", 
				"QuestionId"=>$data["QuestionId"],
			);
			$im=new ModelInterest();
			$im->reduceInterest($qd);
			
		}		
		return $data;
	}
	/**查找用户所有收藏（并生成可显示方式） */
	public function getAllByUserId($uid,$page=1,$num=5)
	{
		$cm=new ModelCollection();
		$qm=new ModelQuestion;
		$min=Tools::getPageRange($page,$num);

		$cs=$cm->getCollectionsByUserId($uid,$min,$num);
		$qm->replaceQuestionId($cs);
		return $cs;
	}
}