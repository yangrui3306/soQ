<?php

/**
 * @author : goodtimp
 * @time : 2019-3-1
*/

namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;


class Focus extends NotORM {

    protected function getTableName($id) {
        return 'focus';
		}
		
    /**
     * 查找某个用户关注的用户
     */
    public function gesFolloweeByUserId($uid,$start=0,$num=0)
     {
       $re= $this->getORM()->where("FanId",$uid);
       if($num>0) return $re->limit($start,$num)->fetchAll();
      return $re->fetchAll();
      }

    /**
     * 查找某个用户的粉丝
     */
    public function gesFansByUserId($uid,$start=0,$num=0) {
      $re= $this->getORM()->where("UserId",$uid);
      if($num>0) return $re->limit($start,$num)->fetchAll();
      return $re->fetchAll();
    }
    /**添加 
     * @param -1已经关注，其他为Id
    */
    public function insertOne($uid,$fid)
    {
      $re=$this->getORM()->where("UserId",$uid)
      ->where("FanId",$fid);
      if($re->count()>0) return -1;
      else {
        $data=array("UserId"=>$uid,"FanId"=>$fid);
        $re->insert($data);
        return $re->insert_id();
      }
    }
}
