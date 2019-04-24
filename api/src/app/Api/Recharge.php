<?php
namespace App\Api;

use PhalApi\Api;
use App\Common\MyStandard;
use App\Domain\Recharge as Domain;

/**
 * 用户金额接口类
 * @author ipso
 */
class Recharge extends Api
{

	public function getRules()
	{
		return array(
			'getByUserID' => array(
				'UserId' => array('name' => 'userId', 'require' => true, 'desc' => '用户ID'),
			),
			'getCoin' => array(
				'UserId' => array('name' => 'userId', 'require' => true, 'desc' => '用户ID'),
			),
			// 'getMoney' => array(
			// 	'UserId' => array('name' => 'userId', 'require' => true, 'desc' => '用户ID'),
			// ),
			// 'fistRecharge' => array(
			// 	'UserId' => array('name' => 'userId', 'require' => true, 'desc' => '用户ID'),
			// 	'Coin' => array('name' => 'coin', 'require' => true, 'desc' => '用户金币'),
			// 	'Money' => array('name' => 'money', 'require' => true, 'desc' => '用户RMB'),
			// ),
			// 'reRecharge' => array(
			// 	'UserId' => array('name' => 'userId', 'require' => true, 'desc' => '用户ID'),
			// 	'Coin' => array('name' => 'coin', 'desc' => '用户金币'),
			// 	'Money' => array('name' => 'money', 'desc' => '用户RMB'),
			// ),
			'rechargeMoney' => array(
				'UserId' => array('name' => 'userId', 'require' => true, 'desc' => '用户ID'),
				'Money' => array('name' => 'money', 'min'=>0,'require' => true, 'desc' => '用户RMB'),
			),
			'updateCoin' => array(
				'UserId' => array('name' => 'userId', 'require' => true, 'desc' => '用户ID'),
				'Coin' => array('name' => 'coin','type'=>'int', 'require' => true,'desc' => '增加的用户金币，可以为负数')
			)
		);
	}

	/**
	 * 获取用户金额信息
	 * @param UserId 用户ID
	 */
	public function getByUserID()
	{
		$domain = new Domain();
		$res = $domain->getCoin($this->UserId);

		if ($res == -1) return MyStandard::gReturn(1, '获取失败');
		return MyStandard::gReturn(0, $res, '获取成功');
	}

	/**
	 * 获取用户金币
	 * @param UserId 用户ID
	 */
	public function getCoin()
	{
		$domain = new Domain();
		$res = $domain->getCoin($this->UserId);

		if ($res == -1) return MyStandard::gReturn(1, '获取失败');
		return MyStandard::gReturn(0, $res, '获取成功');
	}

	// /**
	//  * 获取用户人民币
	//  * @param UserId 用户ID
	//  */
	// public function getMoney(){
	// 	$domain = new Domain();
	// 	$res = $domain -> getMoney($this -> UserId);
	// 	if($res == 0) return MyStandard::gReturn(1,'获取失败');
	// 	return MyStandard::gReturn(0, $res,'获取成功');
	// }

	/**
	 * 用户第一次充值
	 * @param UserId 用户ID
	 * @param Coin   用户金币
	 * @param Money  用户RMB
	 */
	public function FirstRecharge()
	{
		$domain = new Domain();
		$data = array(
			'UserId' => $this->UserId,
			'Coin'   => $this->Coin,
			'Money'  => $this->Money,
		);
		$res = $domain->FirstRecharge($data);
		if ($res == 0) return MyStandard::gReturn(1, '', '操作失败');
		return MyStandard::gReturn(0, '', '第一次充值成功');
	}

	/**
	 * 用户金额变动
	 * @param UserId 用户ID
	 * @param Coin   用户金币/若是消费，Coin参数应为负数
	 * @param Money  用户RMB
	 */
	public function reRecharge()
	{
		$domain = new Domain();
		$data = array(
			'UserId' => $this->UserId,
			'Coin'   => $this->Coin,
			'Money'  => $this->Money,
		);
		$res = $domain->reRecharge($data);
		if ($res == 0) return MyStandard::gReturn(1, '', '操作失败');
		return MyStandard::gReturn(0, '', '金额变动成功');
	}

	/**
	 * 用户金额变动
	 * @param UserId 用户ID
	 * @param Money  用户RMB
	 */
	public function rechargeMoney()
	{
		$domain = new Domain();
		$data = array(
			'UserId' => $this->UserId,
			'Money'  => $this->Money,
		);
		$res = $domain->rechargeMoney($data);
		if ($res == -1) return MyStandard::gReturn(1, '', '操作失败');
		return MyStandard::gReturn(0, $res, '人民币充值成功');
	}

	/**
	 * 更改用户金额
	 *
	 * @return 返回修改后的用户金额
	 */
	public function updateCoin(){
		$domain=new Domain();
		$res=$domain->updateCoin($this->UserId,$this->Coin);

		if ($res == -1) return MyStandard::gReturn(0, -1, '余额不足');
		if ($res === false) return MyStandard::gReturn(1, '', '更新出错');
		return MyStandard::gReturn(0, $res, '修改成功');
	}
}
