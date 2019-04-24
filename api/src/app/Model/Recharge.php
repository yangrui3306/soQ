<?php 
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Recharge extends NotORM{
	protected function getTableName($id)
  {
    return 'recharge';
	}

	/* -----------------   数据库查询操作   ------------------- */

	public function getById($Id){
		$model = $this -> getORM();
		return $model -> where('Id', $Id) -> fetchOne();
	}

	public function getByUserId($userId){
		$model = $this -> getORM();
		return $model -> where('UserId', $userId) -> fetchOne();
	}


	/* -----------------   数据库插入操作   ------------------- */

		/**
		 * 用户初始充值
		 * @param data 用户充值信息数组
		 */
		public function insertOne($data){
			$model = $this -> getORM();
			$data["RechargeTime"]=strtotime('now');
			$sql = $model -> insert($data);
			if($sql){
				return $data;
			}
			return $sql;
		}

		/* -----------------   数据库更新操作  -------------------- */

		/**
		 * 用户二次充值/消费
		 * @param data 用户充值信息数组
		 */
		public function updateOne($data){
			$model = $this -> getORM();
			$sql = $model -> where('UserId', $data['UserId']) -> update($data);
			return $sql;
		}
}