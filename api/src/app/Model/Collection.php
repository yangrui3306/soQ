<?php

/**
 * @author : goodtimp
 * @time : 2019-3-1
*/

namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;


class Collection extends NotORM
{

	/* --------------   数据库查询   ---------------- */

    protected function getTableName($id)
    {
        return 'collection';
    }

    /**
     * 根据用户ID查找所有收藏的题目Id
     * @return 题目Id的数组
     */
    public function getCollectionsByUserId($uid)
    {
        return $this->getORM()
            ->where('UserId', $uid)
            ->select('QuestionId')
            ->fetchAll();
		}
		


		/* --------------   数据库插入   ---------------- */

		public function insertOne($data){
			$model = $this -> getORM();
			return $model -> insert($data);
		}


		/* --------------   数据库更新   ---------------- */

		public function updateOne($data){
			$model = $this -> getORM();
			return $model -> where("Id", $data['Id']) -> update($data);
		}


		/* --------------   数据库删除   ---------------- */

		public function deleteOne($uid){
			$model = $this -> getORM();
			return $model -> where('Id', $uid) -> delete();
		}
}
