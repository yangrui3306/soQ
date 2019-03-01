<?php

/**
 * @author : goodtimp
 * @time : 2019-3-1
*/

namespace App\Model\Examples;

use PhalApi\Model\NotORMModel as NotORM;


class Note extends NotORM {

    protected function getTableName($id) {
        return 'note';
    }
    /**
     * 根据用户id查找笔记
     */
    public function getNotesByUserId($userid) {
        return $this->getORM()
            ->select('*')
            ->where('UserId', $userid)
            ->fetchAll();
    }
    /**
     * 根据笔记id查找笔记
     */
    public function getNoteById($id) {
        return $this->getORM()
            ->select('*')
            ->where('Id', $id)
            ->fetchAll();
    }
    /**
     * 根据分类Id查找笔记
     */
    public function getNotesByCateId($cateid) {
        return $this->getORM()
            ->select('*')
            ->where('CategoryId', $cateid)
            ->fetchAll();
    }
    
}
