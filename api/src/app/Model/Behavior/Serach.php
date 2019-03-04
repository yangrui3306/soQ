<?php
/**
 * @author : goodtimp
 * @time : 2019-3-4
*/

namespace App\Model\Behavior;

use PhalApi\Model\NotORMModel as NotORM;


class Serach extends NotORM
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
     * 根据用户ID查找所有收藏操作
     * @param $bs 数据库可操作的Behavior表
     * @return 返回数据库可操作类型
     */
    public function mGetCollectionsByUserId($uid,$bs=null)
    {
        if($bs==null) $bs=$this->getORM()->where('UserId',$uid);
        return $this->getORM()
            ->select('*')
            ->where('UserId', $uid)
            ->where('Type',3);
    }
    /**
     * 根据用户ID查找所有点赞操作
     * @return 返回数据库可操作类型
     */
    public function mGetLikeByUserId($uid,$bs=null)
    {
        if($bs==null) $bs=$this->getORM()->where('UserId',$uid);
        return $bs->where('Type',2);
    }
    /**
     * 根据用户ID查找所有点赞操作
     * @return 返回数据库可操作类型
     */
    public function mGetSearchByUserId($uid,$bs=null)
    {
        if($bs==null) $bs=$this->getORM()->where('UserId',$uid);
        return $bs->where('Type',1);
    }

    /**
     * 根据用户ID查找所有错题整理操作
     * @return 返回数据库可操作类型
     */
    public function mGetMistakeByUserId($uid,$bs=null)
    {
        if($bs==null) $bs=$this->getORM()->where('UserId',$uid);
        return $bs->where('Type',4);
    }
}
