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
            ->order("Id DESC")
            ->fetchAll();
    }
    /**
     * 根据用户ID查找所有收藏操作
     * @param $bs 数据库可操作的Behavior表
     * @return 返回数据库可操作类型
     */
    public function mGetCollectionsByUserId($uid,$date=-1,$num=0,$bs=null)
    {
        return $this->mGetType($uid,$date,$num,$bs)->where('Type',3);
    }
    /**
     * 根据用户ID查找所有点赞操作
     * @return 返回数据库可操作类型
     */
    public function mGetLikeByUserId($uid,$date=-1,$num=0,$bs=null)
    {
        return $this->mGetType($uid,$date,$num,$bs)->where("Type",2);
    }
    /**
     * 根据用户ID查找所有搜索操作
     * @return 返回数据库可操作类型
     */
    public function mGetSearchByUserId($uid,$date=-1,$num=0,$bs=null)
    {
        return $this->mGetType($uid,$date,$num,$bs)->where('Type',1);
    }

    /**
     * 根据用户ID查找所有错题整理操作
     * @return 返回数据库可操作类型
     */
    public function mGetMistakeByUserId($uid,$date=-1,$num=0,$bs=null)
    {
        
        return $this->mGetType($uid,$date,$num,$bs)->where('Type',4);
    }
    /**
     * 根据题目的分类获取行为表中的数据
     */
    public function mGetByQuestionCategory($cid,$bs=null)
    {
        if($bs==null) $bs=$this->getORM();
        $sql = "SELECT QuestionId FROM `behavior`,`question` where behavior.QuestionId=question.Id and question.CategoryId=:cid";
        $params=array(':cid'=>$cid);

        return $bs->queryRows($sql,$params);
    }
    /**
     * Common 得到某一个类型的操作
     * @param uid 用户Id
     * @param date 几天前
     * @param num 前n条数据
     * @return 返回数据库可操作类型
     */
    private function mGetType($uid,$date=-1,$num=0,$bs=null)
    {
        if($bs==null) $bs=$this->getORM()->where('UserId',$uid);
       
        if($date!=-1) 
        {
            $chuo=strtotime("-".$date." Days");//得到前$date天时间戳
            $time=date("Y-m-d",$chuo);//时间戳转换
            $time=$time." 00:00:00";
            $bs=$bs->where("Date >= ?" ,$time);
        }
        $bs=$bs->order('Id DESC');//降序排序,获取最新的数据
        if($num>0)
        {
            $bs=$bs->limit($num);
        }
        
        return $bs;
    }
}
