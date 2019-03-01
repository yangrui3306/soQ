<?php

/**
 * @author : goodtimp
 * @time : 2019-3-1
*/

namespace App\Model\Examples;

use PhalApi\Model\NotORMModel as NotORM;


class Notecategory extends NotORM {

    protected function getTableName($id) {
        return 'notecategory';
		}
		
    /**
     * 根据用户Id查找所有笔记分类
		 * @param userid 用户id
     */
    public function getNotesCategoryByUserId($userid) {
        return $this->getORM()
            ->select('*')
            ->where('UserId',$userid)
            ->fetchAll();
    }
}
