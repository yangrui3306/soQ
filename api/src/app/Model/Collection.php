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
	public function getCollectionQuestionsByUserId($uid)
	{
		return $this->getORM()
			->where('UserId', $uid)
			->select('QuestionId')
			->fetchAll();
	}
	
	public function getCollectionsByUserId($uid,$start=0,$num=0)
	{
		$re=$this->getORM()
		->where('UserId', $uid);
		if($num>0)
		{
			return $re->limit($start,$num)->fetchAll();
		}
		return $re->fetchAll();
	}

	/* --------------   数据库插入   ---------------- */

	public function insertOne($data)
	{

		$orm = $this->getORM();
		$orm->insert($data);

		// 返回新增的ID（注意，这里不能使用连贯操作，因为要保持同一个ORM实例）
		return $orm->insert_id();
	}


	/* --------------   数据库更新   ---------------- */

	public function updateOne($data)
	{
		$model = $this->getORM();
		return $model->where("Id", $data['Id'])->update($data);
	}


	/* --------------   数据库删除   ---------------- */

	/**删除并得到数据,或者返回0 */
	public function deleteOne($id)
	{
		$model = $this->getORM()->where('Id', $id);
		$data = $model->fetchOne();
		$model->where('Id', $id)->delete();
		return $data ? $data : 0;
	}
}
