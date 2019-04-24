<?php
namespace App\Domain;

use App\Model\Recharge as Model;
use App\Model\User as UserModel;
use App\Common\Tools as Tools;
class Recharge {
	/**
	 * 获取用户金币
	 * @param UserId 用户ID
	 */
	public function getCoin($UserId){
		$model = new UserModel();
		$sql = $model ->getUserById($UserId);
		if(!$sql){
			return -1;
		}
		return $sql['Coin'];
	}

	/**
	 * 获取用户人民币
	 * @param UserId 用户ID
	 */
	public function getMoney($UserId){
		$model = new Model();
		$sql = $model -> getByUserId($UserId);
		if(!$sql){
			return 0;
		}
		return $sql['Money'];
	}

	/**
	 * 获取用户金额信息
	 * @param UserId 用户ID
	 */
	public function getByUserID($UserId){
		$model = new Model();
		$sql = $model -> getByUserId($UserId);
		if(!$sql){
			return 0;
		}
		return $sql;
	}

	/**
	 * 用户第一次充值
	 * @param data 用户充值信息
	 */
	public function FirstRecharge($data){
		$model = new Model();
		$sql = $model -> insertOne($data);
		if(!$sql){
			return 0;
		}
		return 1;
	}

	/**
	 * 用户金额发生变化(人民币兑换金币或者是消费、获得金币)
	 * @param data 用户充值信息
	 */
	public function reRecharge($data){
		$model = new Model();
		$curr = $model -> getByUserId($data['UserId']);

		if(isset($data['Money']) == true){ // 人民币兑换金币
			$data['Coin'] = $data['Money'] * 10 + $curr['Coin'];
			$data['Money'] += $curr['Money'];
		}else{ // 消费、获得金币
			$data['Coin'] += $curr['Coin'];
		}

		$sql = $model -> updateOne($data);
		if(!$sql){
			return 0;
		}
		return 1;
	}

	/**
	 * 用户人民币充值
	 * @param data 用户充值信息
	 */
	public function rechargeMoney($data){
		$model = new Model();
		$data["Coin"] = $data["Money"]*10;
	
		$sql=$model->insertOne($data);
		if(!$sql){
			return -1;
		}
		$newcon=$this->updateCoin($data["UserId"],$data["Coin"]);
		$sql["NewCoin"]=$newcon;
		return $sql;
	}	
	/**
	 * 更改用户余额
	 * @return -1 余额不足，>=0 成功,false更新出错
	 */
	public function updateCoin($uid,$coin)
	{
		$model = new UserModel();
		$sql = $model ->getUserById($uid);
		$old=$sql["Coin"];
		$new=$old+$coin;
		if($new<0) return -1;
		else{
			return Tools::updateUserCoin($uid,$new);
		}
	}
}