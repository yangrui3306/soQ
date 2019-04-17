<?php
namespace App\Domain;
use App\Model\Notice as Model;

class Notice {
	/**
	 * 添加
	 */
	public function add($data){
		$model = new Model();
		$sql = $model -> insertOne($data);
		return $sql;
	}
	/**
	 * 通过用户id，得到用户已读未读的消息
	 */
	public function getByUserId($uid){
		$model= new Model();
		$re["Read"]=$model->getReadByUserId($uid);
		$re["UnRead"]=$model->getUnreadByUserId($uid);
		return $re;
	}
	/**
	 * 更改已读未读状态
	 * @param id 需要更改的通知id
	 * @param uid 需要更改的人的id
	 * @param rtou bool类型是否为从未读变成已读，默认为true
	 */
	public function updateReader($id,$uid,$rtou=true){
		$model= new Model();
		$data=$model->getById($id);
		$str=",".$uid.",";
		if($rtou)	{
			$data["AcceptId"]=str_replace($str,",",$data["AcceptId"]);// 替换
			if(strstr($data["ReadId"],$str)==null)
				$data["ReadId"]=$data["ReadId"].$uid.",";

		}
		else {
			$data["ReadId"]=str_replace($str,",",$data["ReadId"]);// 替换
			if(strstr($data["AcceptId"],$str)==null)
				$data["AcceptId"]=$data["AcceptId"].$uid.",";
		}
		return $model->update($id,$data);
	}
}