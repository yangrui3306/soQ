<?php

namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;


class User extends NotORM {

    protected function getTableName($id) {
        return 'user';
		}
		
		/* --------------   数据库查询操作   ---------------- */

		/**
	 	 * 获取所有行数据
	 	 * @author ipso
	 	 */
		public function getAll(){
			$model = $this -> getORM();
			return $model -> fetchAll();
		}

		public function getSex($name){
			$model = $this -> getORM();
			return $model -> where('Id', $name) -> fetchOne();
		}

		public function getByPhone($phone){
			$model = $this -> getORM();
			return $model -> where('Phone', $phone) -> fetchOne();
		}

		public function getByName($name){
			$model = $this -> getORM();
			return $model -> where('Name', $name) -> fetchOne();
		}

    /**
     * 根据用户ID查找用户
     */
    public function getUserById($id) {
			$model = $this -> getORM();
        return $model->select('*')
          ->where('Id',$id)
          ->fetchAll();
		}
		
		/* -----------------   数据库插入操作   ------------------- */

		/**
		 * 增加一条用户记录
		 * @param data 用户信息数组
		 */
		public function insertOne($data){
			$model = $this -> getORM();
			$sql = $model -> insert($data);
			return $sql;
		}

		/* -----------------   数据库更新操作  -------------------- */

		public function updateOne($data){
			$model = $this -> getORM();
			$sql = $model -> where('Id', $data['Id']) -> update($data);
			return $sql;
		}

		/* -----------------   数据库删除操作  -------------------- */
		
		public function deleteOne($uid){
			$model = $this -> getORM();
			$sql = $model -> where("Id", $uid) -> delete();
		}
}
