<?php

/**
 * @author : goodtimp
 * @time : 2019-3-10
*/

namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;


class Interest extends NotORM
{
    const Behavior = array('Like' => 0.5, 'Collection' => 0.7, 'Mistake' => 1, 'Search' => 0.2);

    protected function getTableName($id)
    {
        return 'interest';
    }
    /** 
     * 添加记录，如果存在则相加
     * @param data {"UserId","QuestionId","Behavior"}
     * @return 不返回 
     */
    public function addInterest($data)
    {
        $time=date('Y-m-d h:i:s', time());
        $data["DateTime"]=$time;
        
        $re = $this->getORM()
            ->where("UserId", $data["UserId"])
            ->where("QuestionId", $data["QuestionId"]);
        if ($re->count() > 0) {
            $re->update(array(
                'DateTime'=>$time,
                'Interestingness' => new \NotORM_Literal("Interestingness + " . Interest::Behavior[$data["Behavior"]])
            ));
        } else {
            $data["Interestingness"]=Interest::Behavior[$data["Behavior"]];
            unset($data["Behavior"]);//删除多余键值对
            $re->insert($data);
        }
    }
    /** 
     * 减少兴趣度
     * @param data {"UserId","QuestionId","Behavior"}
     * @return 不返回 
     */
    public function reduceInterest($data)
    {
        $time=date('Y-m-d h:i:s', time());
        $data["DateTime"]=$time;
        
        $re = $this->getORM()
            ->where("UserId", $data["UserId"])
            ->where("QuestionId", $data["QuestionId"]);
        if ($re->count() > 0) {
            $re->update(array(
                'DateTime'=>$time,
                'Interestingness' => new \NotORM_Literal("Interestingness - " . Interest::Behavior[$data["Behavior"]])
            ));
        } 
    }
    /**
     * 得到用户兴趣度
     * @param uid 用户id
     * @param date 从某段时间以内
     * @param num 记录数量大于num
     * @return {UserId,QuestionId,Interestingness} 
     */
    public function getInterestByUserId($uid,$date,$num=0)
    {
        $re=$this->getORM()->where("UserId",$uid)->where("DateTime > ?",$date);
        return $re;
    }
}
