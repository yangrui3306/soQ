<?php

/**
 * @author : goodtimp
 * @time : 2019-3-1
*/

namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;


class Notecategory extends NotORM {

    protected function getTableName($id) {
        return 'notecategory';
		}
		
    /**
     * 根据用户Id查找所有笔记分类
		 * @param userid 用户id
     */
    public function getNotesCategoryByUserId($userid) {
        return $this->getORM()
            ->select('*')
            ->where('UserId',$userid)
            ->fetchAll();
    }
    /**
     * 添加笔记分类
     * @return -1已存在 返回Id
     */
    public function addCategory($data){
        $orm = $this->getORM();
        $judge=$orm->where("UserId",$data["UserId"])->where("Name",$data["Name"])->count();
        if($judge>0) return -1;
        $orm->insert($data);
      
        // 返回新增的ID（注意，这里不能使用连贯操作，因为要保持同一个ORM实例）
        return $orm->insert_id();
    }


    /**
     * 判断用户是否含有该笔记分类
		 * @param userid 用户id
         * @param cateid
         * @return 0为否，1为有
     */
    public function judgeCateForUser($userid,$cateid) {
        return $this->getORM()
            ->select('*')
            ->where('UserId',$userid)
            ->where('Id',$cateid)
            ->count();
		}
		
		/**
		 * @author ipso
		 */
		public function getCidByName($name){
			$model = $this -> getORM();
			return $model -> where('Name', $name) -> select("Id") -> fetchOne();
        }
        	
		/**
		 * @author goodtimp
		 */
		public function getNameById($id){
			$model = $this -> getORM();
			return $model -> where('Id', $id) -> select("Name") -> fetchOne()["Name"];
		}
}
