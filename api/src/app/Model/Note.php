<?php

/**
 * @author : goodtimp
 * @time : 2019-3-1
*/

namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;
use App\Common\Match;

class Note extends NotORM
{

    protected function getTableName()
    {
        return 'note';
    }

    /**
     * 根据用户id查找笔记
		 * @param userid 用户id
     */
    public function getNotesByUserId($userid, $num = 0)
    {
        $notes = $this->getORM()
            ->select('*')
            ->where('UserId', $userid)
            ->order("Id DESC");
        if ($num > 0) {
                $notes = $notes->limit($num);
            }
        return $notes;
    }

    /**
     * 根据笔记id查找笔记
		 * @param id 笔记id
     */
    public function getNoteById($id)
    {
        return $this->getORM()
            ->select('*')
            ->where('Id', $id)
            ->fetchAll();
    }

    /**
     * 根据分类Id查找笔记
		 * @param cateid 分类id
         * @param num 获取前几个
     */
    public function getNotesByCateId($cateid, $num = 0)
    {
        $res = $this->getORM()
            ->select('*')
            ->where('CategoryId', $cateid)->order('Id DESC');
        if ($num == 0) return $res->fetchAll();
        else return $res->limit($num)->fetchAll();
    }

    /**根据关键字查找用户笔记 */
    public function getNotesByKeywords($uid, $keys)
    {
        $s = Match::AllWordMatch($keys);
        return $this->getORM()->where("UserId", $uid)->where("(Content,Headline) LIKE ?", $s)
            ->order("Id DESC")->fetchAll();
    }
}
