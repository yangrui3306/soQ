<?php
/**
 * @author : goodtimp
 * @time : 2019-3-4
*/

namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;


class Behavior extends NotORM
{

    protected function getTableName($id)
    {
        return 'behavior';
    }

    /**
     * 根据用户ID查找所有所有操作
     */
    public function getCollectionsByUserId($uid)
    {
        return $this->getORM()
            ->select('*')
            ->where('UserId', $uid)
            ->fetchAll();
    }
    /**
     * 根据用户ID查找所有所有操作
     * @return 返回数据库可操作类型
     */
    public function mGetCollectionsByUserId($uid)
    {
        return $this->getORM()
            ->select('*')
            ->where('UserId', $uid);
    }
}
