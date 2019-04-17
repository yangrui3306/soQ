<?php
namespace App\Domain;
use App\Model\Test as Model;
use App\Domain\Notice as NoticeDomain;
use App\Model\User as UserModel;
use App\Model\Userelation as relationModel;
use App\Model\Question\Basic as BasicModel;
class Test {
	/**
	 * 发布测试开始通知
	 */
	private function noticeByStatus($id,$data){
		$model = new Model();
		$data = $model->getById($id);
		$noticeDomain = new NoticeDomain();
		$userModel = new UserModel();
		$reModel=new relationModel();
		$teacher = $userModel -> getUserById($data['TeacherId']);
		$relation=$reModel->getById($data['UserrelationId']);

		$notice = array(
			'Title'    => '测试发布通知',
			'Content'  => '发布新测试，请同学们积极参与。',
			'Author'   => $teacher['Name'],
			'Ctime'    => date('Y-m-d H:i:s'),
			'AcceptId' => $relation["Sid"].$data['TeacherId'].",",
			'Handle' => "[TestId:".$data["Id"]."]",
		);
		$noticeDomain -> add($notice);
	}
	/**
	 * 添加测试
	 */
	public function add($data){
		$model = new Model();
		$id = $model -> insertOne($data);
		if($id==0) return $id;
		else if($data["Status"]==1){
			$this->noticeByStatus($id,$data);
		}
		return $id;
	}
	public function startTest($data){
		$model = new Model();
	}
	/**
	 * 更新数据
	 * @return -1为更新异常，0为无变化。返回受影响行数 
	 */
	public function update($Id,$data)
	{
		
		$model =new Model();
		$temp = $model->getById($Id);	
		$isId = $model -> updateOne($Id,$data);
		
		if($isId==false) return -1;
		else if($isId==0) return 0;
	
		// 如果更改了测试状态，则新建一条通知
		if(array_key_exists("Status",$data) && $temp["Status"]==0 &&$data['Status'] == 1){
			$this->noticeByStatus($Id,$data);
		}
		return $isId;
	}
	/**
	 * 测试详情
	 */
	public function getByTestDetail($Id){
		$model =new Model();
		$data = $model->getById($Id);	
		$bm=new BasicModel();
		//QuestionId
		$ids=explode(",",$data["Content"]);
		$qs=array();
		for($i=0;$i<count($ids);$i++){
			$qs[$i]["QuestionId"]=$ids[$i];
		}
	
		$bm->replaceQuestionId($qs);
		for($i=0;$i<count($ids);$i++){
			$qs[$i]=$qs[$i]["Question"];
		}
		$data["Questions"]=$qs;
		return $data;
	}
}