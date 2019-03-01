<?php

/**
 * @author : goodtimp
 * @time : 2019-3-1
*/

namespace App\Model\Examples;

use PhalApi\Model\NotORMModel as NotORM;


class User extends NotORM {

    protected function getTableName($id) {
        return 'user';
    }
    /**
     * 根据用户ID查找用户
     */
    public function getUserById($id) {
        return $this->getORM()
            ->select('*')
            ->where('Id',$id)
            ->fetchAll();
    }
}
