<?php

/**
 * @author : goodtimp
 * @time : 2019-3-1
*/

namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;


class Like extends NotORM
{

	/* --------------   数据库查询   ---------------- */

    protected function getTableName($id)
    {
        return "`like`";//表名与关键字冲突已修改
    }

    /**
     * 根据用户ID查找所有点赞的题目Id
     * @return 题目Id的数组
     */
    public function getLikeQuestionByUserId($uid)
    {
        return $this->getORM()
            ->where('UserId', $uid)
            ->select('QuestionId')
            ->fetchAll();
		}
		
		/**判断用户是否点赞过某题目 */
		public function judgeUserLikeQuestion($uid,$qid){
			$re=$this->getORM()->where("UserId",$uid)->where("QuestionId",$qid)->count();
			return $re==0?false:true;
		}

		/* --------------   数据库插入   ---------------- */
    
		public function insertOne($data){
			$orm = $this->getORM();
			$orm->insert($data);
	
			// 返回新增的ID（注意，这里不能使用连贯操作，因为要保持同一个ORM实例）
			return $orm->insert_id();
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
