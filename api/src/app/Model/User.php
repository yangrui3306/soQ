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

		public function getByPhone($phone){
			$model = $this -> getORM();
			return $model -> where('Phone', $phone) -> fetchOne();
		}

		public function getByName($name){
			$model = $this -> getORM();
			return $model -> where('Name', $name) -> fetchOne();
		}

		public function getUidByName($name){
			$model = $this -> getORM();
			return $model -> where("Name", $name) -> select("Id") -> fetchOne();
		}

		public function getUidByPhone($phone){
			$model = $this -> getORM();
			return $model -> where("Name", $phone) -> select("Id") -> fetchOne();
		}

		public function getUid(){
			$model = $this -> getORM();
			return $model -> select("Id") -> fetchAll();
		}

		public function getTeachers(){ // 获取所有教师
			$model = $this -> getORM();
			return $model -> where('Occupation', 2) -> fetchAll();
		}

		public function getCount($type){  // 获取学生或教师数量
			$model = $this -> getORM();
			return $model -> where('Occupation', $type) -> count('Id');
		}

		public function getUserByOcc($type, $begin, $num){  // 获取老师或学生所有行
			$model = $this -> getORM();
			return $model -> where('Occupation', $type) -> limit($begin, $num) -> fetchAll();
		}

    /**
     * 根据用户ID查找用户
     */
    public function getUserById($id) {
			$model = $this -> getORM();
        return $model->select('*')
          ->where('Id',$id)
          ->fetchOne();
		}

		/**将数组中UserId字段更改为User
		 * @param $keys 默认替换UserId，可传入
		 */
		public function replaceUserId(&$data,$keys="UserId")
		{
			for($i=0;$i<count($data);$i++)
			{
				if(array_key_exists($keys,$data[$i]))
				{
					$data[$i]["User"]=$this->getUserById($data[$i][$keys]);
				}
			}
			return $data;
		}

		
		/* -----------------   数据库插入操作   ------------------- */

		/**
		 * 增加一条用户记录
		 * @param data 用户信息数组
		 * @return 插入Id
		 */
		public function insertOne($data){
			$orm = $this->getORM();
				$data["DateTime"] = date('Y-m-d h:i:s', time());
        $orm->insert($data);

        // 返回新增的ID（注意，这里不能使用连贯操作，因为要保持同一个ORM实例）
        return $orm->insert_id();
		}

		/* -----------------   数据库更新操作  -------------------- */

		public function updateOne($data){
			$model = $this -> getORM();
			$sql = $model -> where('Id', $data['Id']) -> update($data);
			if($sql>0)
			{
				$sql=$model->where('Id',$data['Id'])->fetchOne();
			}
			return $sql;
		}

		/* -----------------   数据库删除操作  -------------------- */
		
		public function deleteOne($uid){
			$model = $this -> getORM();
			$sql = $model -> where("Id", $uid) -> delete();
		}
}
