<?php
namespace App\Domain;

use App\Model\Note as ModelNote;
use App\Model\Notecategory as ModelCate;


class Note {
	
    /**
     * 根据分类Id查找笔记
		 * @param cateid 分类id
     * @param num 获取前几个
     */
    public function getNotesByCateId($uid,$cateid, $num = 0)
    {
      $m=new ModelNote();
      $cm=new ModelCate();
      if($cm->judgeCateForUser($uid,$cateid)>0){
        return $m->getNotesByCateId($cateid,$num);
      }
      else return [];
    }

    /**根据关键字查找用户笔记 */
    public function getNotesByKeywords($uid, $keys)
    {
      $m=new ModelNote;
      return $m->getNotesByKeywords($uid,$keys);
		}
		
		/**
		 * 增加一条笔记
		 * @param  data  包含了用户笔记信息的数组，其中data['NoteCategory']可能是分来id或者是分类名称
		 * @author ipso
		 * 
		 */
		public function add($data){
			// 判断NoteCategory是分类的id还是name
			$flag = is_int($data['NoteCategory']);
			if($flag == true){
				$data['NoteCategoryId'] = $data['NoteCategory'];
				unset($data['NoteCategory']);
			}else{
				$cateModel = new ModelCate();
				$cateid = $cateModel -> getCidByName($data['NoteCategory']);
				$data['NoteCategoryId'] = $cateid;
				unset($data['NoteCategory']);
			}

			// 将数据写入数据库
			$model = new Model();
			$sql = $model -> insertOne($data);
			return $sql;
		}

		/**
		 * 更新一条数据
		 * @param  data  包含了用户笔记信息的数组，其中data['NoteCategory']可能是分来id或者是分类名称
		 * @author ipso
		 */
		public function update($data){
			$flag = is_int($data['NoteCategory']);
			if($flag == true){
				$data['NoteCategoryId'] = $data['NoteCategory'];
				unset($data['NoteCategory']);
			}else{
				$cateModel = new ModelCate();
				$cateid = $cateModel -> getCidByName($data['NoteCategory']);
				$data['NoteCategoryId'] = $cateid;
				unset($data['NoteCategory']);
			}
			// 将数据写入数据库
			$model = new Model();
			$sql = $model -> updateOne($data);
			return $sql;
		}

		public function delete($nid){
			return $sql;
		}

		/**
		 * 获取笔记数量
		 */
		public function getCount(){
			$model = new Model();
			return $model -> getCount();
		}

		/**
		 * 获取limit限制内的所有记录
		 * @param  begin  开始位置
		 * @param  length 获取记录的数量
		 * @author ipso
		 */
		public function getByLimit($begin, $length=10){
			$model = new Model();
			$sql = $model -> getByLimit($begin,$length);
			return $sql;
		}
}