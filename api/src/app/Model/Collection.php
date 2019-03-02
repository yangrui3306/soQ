<?php

/**
 * @author : goodtimp
 * @time : 2019-3-1
*/

namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;


class Collection extends NotORM
{

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
}
