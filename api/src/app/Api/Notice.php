<?php
namespace App\Api;
use App\Model\Notice as Model;
use PhalApi\Api;
use App\Common\MyStandard;
use App\Domain\Notice as Domain;

/**
 * 通知类接口
 */
class Notice extends Api {

	public function getRules(){
		return array(
			'add' => array(
				'Status'  => array('name' => 'Status', 'require' => true, 'default' => 0, 'desc' => '消息状态：0未读，1已读'),
				'Title'  => array('name' => 'Pass', 'desc' => '通知标题'),
				'Author' => array('name' => 'Phone', 'desc' => '发送人'),
				'Content' => array('name' => 'Content', 'desc' => '通知内容'),
				'AcceptId' => array('name' => 'AcceptId', 'desc' => '接收者Id'),
			),
			'getCount' => array(
			),
			'getByUserId'=>array(
				'UserId' => array('name' => 'UserId', 'require' => true, 'desc' => '用户Id'),
			),
			'getList' => array(
				'Page'  => array('name' => 'Page',  'desc' => '当前页'),
				'Number' => array('name' => 'Number', 'desc' => '每页数量'),
			),
			'update' => array(
				'Id' => array('name' => 'Id', 'desc' => '通知Id'),
				'Status'  => array('name' => 'Status', 'require' => true, 'default' => 0, 'desc' => '消息状态：0未读，1已读'),
				'Title'  => array('name' => 'Pass', 'desc' => '通知标题'),
				'Author' => array('name' => 'Phone', 'desc' => '发送人'),
				'Content' => array('name' => 'Content', 'desc' => '通知内容'),
				'AcceptId' => array('name' => 'AcceptId', 'desc' => '接收者Id'),
			),
			'delete' => array(
				'Id'  => array('name' => 'Id', 'require' => true, 'desc' => '当前页'),
			),
			'updateRead'=>array(
				'Id' => array('name' => 'Id', 'require' => true,'desc' => '通知Id'),
				'UserId' => array('name' => 'UserId', 'require' => true, 'desc' => '用户Id'),
				'ToReaded' => array('name' => 'ToReaded','type'=>'boolean', 'default' => true, 'desc' => '是否标记已读？'),
			)
		);
	}

	/**
	 * 添加一条通知
	 */
	public function add(){
		$model = new Model();
		$data = array(
			'Status'   => $this -> Status,
			'Title'    => $this -> Title,
			'Content'  => $this -> Content,
			'Author'   => $this -> Author,
			'Ctime'    => date('Y-m-d H:i:s'),
			'AcceptId' => $this -> AcceptId,
		);
		$sql = $model -> insertOne($data);
		if(!$sql){
			return MyStandard::gReturn(1,'', '添加失败');
		}
		return MyStandard::gReturn(0,$sql, '添加成功');
	}

	/**
	 * 获取通知数量
	 */
	public function getCount(){
		$model = new Model();
		$count = $model -> getCount();
		return MyStandard::gReturn(0,$count, '获取成功');
	}

	/**
	 * 根据Id更新一条数据 更改通知状态也属于更新
	 */
	public function update(){
		$Id = $this -> Id;
		$model = new Model();
		$data = array(
			'Status'  => $this -> Status,
			'Title'   => $this -> Title,
			'Content' => $this -> Content,
			'Author'   => $this -> Author,
			'AcceptId' => $this -> AcceptId,
		);
		$sql = $model -> updateOne($Id, $data);
		if(!$sql){
			return MyStandard::gReturn(1,0, '更新失败');
		}
		return MyStandard::gReturn(0,$Id, '更新成功');
	}

	/**
	 * 获取通知列表
	 */
	public function getList(){
		$begin = ($this -> Page - 1) * $this -> Number;
		$model = new Model();
		$list = $model -> getList($begin, $this -> Number);
		if(!$list){
			return MyStandard::gReturn(1,[], '获取失败');
		}
		return MyStandard::gReturn(0,$list, '获取成功');
	}

	/**
	 * 删除一条或多条通知
	 */
	public function delete(){
		$Id = $this -> Id;
		$model = new Model();
		$Ids = explode(',', $Id);
		$count = count($Ids);
		$flag = true;
		$notId = '';
		for($i = 0; $i < $count; $i++){
			$res = $model -> deleteOne($Ids[$i]);
			if(!$res){
				$flag = false;
				$notId = $Ids[$i];
				break;
			}
		}
		if($flag == false){
			return MyStandard::gReturn(1, '', '失败，'.$notId.'不存在');
		}
		return MyStandard::gReturn(0, '', '删除成功');
	}
	/**
	 * 得到用户通知
	 */
	public function getByUserId(){
		$UserId=$this->UserId;
		$domain=new Domain();
		$re=$domain->getByUserId(($UserId));	
		if(!$re){
			return MyStandard::gReturn(1, [], '无数据');
		}
		return MyStandard::gReturn(0, $re, '成功');
	}
	/**
	 * 修改已读未读
	 */
	public function updateRead(){
		$UserId=$this->UserId;
		$Id=$this->Id;
		$to=$this->ToReaded;
		$domain=new Domain();
	
		$re=$domain->updateReader($Id,$UserId,$to);	
		if(!$re){
			return MyStandard::gReturn(1, [], '无数据');
		}
		return MyStandard::gReturn(0, $re, '成功');
	}
}