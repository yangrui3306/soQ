<?php
/**
 * @author : goodtimp
 * @time : 2019-3-4
*/

namespace App\Model\Behavior;

use PhalApi\Model\NotORMModel as NotORM;


class Search extends NotORM
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
    public function mGetCollectionsByUserId($uid,$data=-1,$num=0,$bs=null)
    {
        return $this->mGetType($uid,$data,$num,$bs)->where('Type',3);
    }
    /**
     * 根据用户ID查找所有点赞操作
     * @return 返回数据库可操作类型
     */
    public function mGetLikeByUserId($uid,$data=-1,$num=0,$bs=null)
    {
        return $this->mGetType($uid,$data,$num,$bs)->where("Type",2);
    }
    /**
     * 根据用户ID查找所有搜索操作
     * @return 返回数据库可操作类型
     */
    public function mGetSearchByUserId($uid,$data=-1,$num=0,$bs=null)
    {
        return $this->mGetType($uid,$data,$num,$bs)->where('Type',1);
    }

    /**
     * 根据用户ID查找所有错题整理操作
     * @return 返回数据库可操作类型
     */
    public function mGetMistakeByUserId($uid,$data=-1,$num=0,$bs=null)
    {
        
        return $this->mGetType($uid,$data,$num,$bs)->where('Type',4);
    }
    /**
     * Common 得到某一个类型的操作
     * @return 返回数据库可操作类型
     */
    private function mGetType($uid,$data=-1,$num=0,$bs=null)
    {
        if($bs==null) $bs=$this->getORM()->where('UserId',$uid);
        
        if($data!=-1) 
        {
            $chuo=strtotime("-".$data." Days");//得到前$data天时间戳
            $time=date("Y-m-d",$chuo);//时间戳转换
            $time=$time." 00:00:00";
            $bs=$bs->where("Date >= ?" ,$time);
        }
        if($num!=1)
        {
            //
        }
        return $bs;
    }
}
