<?php

/**
 * @author : goodtimp
 * @time : 2019-3-1
*/

namespace App\Model\User;

use PhalApi\Model\NotORMModel as NotORM;


class User extends NotORM {

    protected function getTableName($id) {
        return 'user';
		}
		
		/**
	 	 * 获取所有行数据
	 	 * @author ipso
	 	 */
		public function getAll(){
			$model = $this->NotORM();
			return $model -> fetchAll();
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
}
