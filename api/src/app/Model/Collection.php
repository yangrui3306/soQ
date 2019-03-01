<?php

/**
 * @author : goodtimp
 * @time : 2019-3-1
*/

namespace App\Model\Examples;

use PhalApi\Model\NotORMModel as NotORM;


class Collection extends NotORM {

    protected function getTableName($id) {
        return 'collection';
    }
    /**
     * 根据用户ID查找所有收藏
     */
    public function getCollectionsByUserId($uid) {
        return $this->getORM()
            ->select('*')
            ->where('UserId',$uid)
            ->fetchAll();
    }
}
