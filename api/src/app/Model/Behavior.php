<?php
/**
 * @author : goodtimp
 * @time : 2019-3-1
*/

namespace App\Model\Examples;

use PhalApi\Model\NotORMModel as NotORM;


class Behavior extends NotORM {

    protected function getTableName($id) {
        return 'behavior';
		}
		
    /**
     * 根据用户ID查找所有所有操作
     */
    public function getCollectionsByUserId($uid) {
        return $this->getORM()
            ->select('*')
            ->where('UserId',$uid)
            ->fetchAll();
    }
}
