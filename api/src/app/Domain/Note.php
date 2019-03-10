<?php
namespace App\Domain;

use App\Model\Note as ModelNote;
use App\Model\Notecategory as ModelCate;
use App\Common\Tools;


class Note {
	/**用户笔记数量 */
	public function getUserNotesNumber($uid)
	{
		$m=new ModelNote();
		return $m->getNotesCountByUserId($uid);
	}
    /**
     * 根据分类Id查找笔记
		 * @param cateid 分类id
     * @param num 获取前几个
     */
    public function getNotesByCateId($cateid,$uid,$page=0, $num = 0)
    {
      $m=new ModelNote();
		
			$min=Tools::getPageRange($page,$num);
			
		  return $m->getNotesByCateId($cateid,$uid,$min,$num);
		}
		/**根据Id得到某个笔记 */
		public function getNoteById($id)
		{
			$m=new ModelNote();
			$cm=new ModelCate();
			$data=$m->getNoteById($id);
			$data["Category"]=$cm->getNameById($data["NoteCategoryId"]);
			return $data;
		}

    /**根据关键字查找用户笔记 */
    public function getNotesByKeywords($uid,$cid=0, $keys)
    {
      $m=new ModelNote;
      return $m->getNotesByKeywords($uid,$cid,$keys);
		}
		
		/**
		 * 增加一条笔记
		 * @param  data  包含了用户笔记信息的数组，其中data['NoteCategory']可能是分来id或者是分类名称
		 * @author ipso
		 * 
		 */
		public function add($data){
		
			 // 判断NoteCategory是分类的id还是name  无需判断
			// $flag = is_int($data['NoteCategory']);
			// if($flag == true){
			// 	$data['NoteCategoryId'] = $data['NoteCategory'];
			// 	unset($data['NoteCategory']);
			// }else{
			// 	$cateModel = new ModelCate();
			// 	$cateid = $cateModel -> getCidByName($data['NoteCategory']);
			// 	$data['NoteCategoryId'] = $cateid;
			// 	unset($data['NoteCategory']);
			// }
			
			// 将数据写入数据库
			$model = new ModelNote();
			$sql = $model -> insertOne($data);
			return $sql;
		}

		/**
		 * 更新一条数据
		 * @param  data  包含了用户笔记信息的数组，其中data['NoteCategory']可能是分来id或者是分类名称
		 * @author ipso
		 */
		public function update($data){
			// $flag = is_int($data['NoteCategory']);
			// if($flag == true){
			// 	$data['NoteCategoryId'] = $data['NoteCategory'];
			// 	unset($data['NoteCategory']);
			// }else{
			// 	$cateModel = new ModelCate();
			// 	$cateid = $cateModel -> getCidByName($data['NoteCategory']);
			// 	$data['NoteCategoryId'] = $cateid;
			// 	unset($data['NoteCategory']);
			// }
			// 将数据写入数据库
			$model = new ModelNote();
			$sql = $model -> updateOne($data);
			return $sql;
		}

		public function delete($nid){
			$model = new ModelNote();
			return $model->deleteOne($nid);
		}

		/**
		 * 获取笔记数量
		 */
		public function getCount(){
			$model = new ModelNote();
			return $model -> getCount(); 
		}

		/**
		 * 获取用户笔记数量
		 */
		public function getCountByUserId($uid){
			$model = new ModelNote();
			return $model -> getCountByUserId($uid); 
		}
		/**
		 * 获取limit限制内的所有记录
		 * @param  begin  开始位置
		 * @param  length 获取记录的数量
		 * @author ipso
		 */
		public function getByLimit($begin, $length=10){
			$model = new ModelNote();
			$sql = $model -> getByLimit($begin,$length);
			return $sql;
		}
}