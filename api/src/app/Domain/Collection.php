<?php
namespace App\Domain;
use App\Model\Collection as ModelCollection;
use App\Model\Mistake as ModelMistake;
use App\Model\Behavior\Basic as ModelBehavior;
use App\Model\Question\Basic as ModelQuestion;
use App\Common\Tools;
class Collection
{
	public function add($data,$StandTime)
	{
		$cm=new ModelCollection();
		$id=$cm->insertOne($data);//添加收藏记录

		$zan=true;
		if($id==-1)
			{
				$cm->deleteOne($data);
				$zan=false;//修改为减少收藏
			}
		if($id==0) return $id;

		if($data["MistakeId"]>0)// 增加相应点赞数量
		{
			$mm=new ModelMistake();
			$mm->collectionMistake($data["MistakeId"],$zan);
		}
		else if($data["QuestionId"]>0)
		{
			$mm=new ModelQuestion;
			$mm->collectionQuestion($data["QuestionId"],$zan);
		}
		if($id==-1) return $id;


		$bm=new ModelBehavior();//添加收藏行为
		$bd=array(
			"UserId"=>$data["UserId"],
			"Type"=>3,
			"QuestionId"=>$data["QuestionId"],
			"MistakeId"=>$data["MistakeId"],
			"StandTime"=>$StandTime
		);
		$bm->addBehavior($bd);

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
		}		
		return 1;
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