<?php
namespace App\Api;

use PhalApi\Api;


use App\Common\MyStandard;
use App\Domain\Behavior\Statistics as ModelStatistics;
use App\Model\Notecategory as ModelNoteCategory;
use App\Domain\Note as DomainNote;

/**
 * 笔记接口类
 * @author ipso
 */
class Note extends Api
{
	public function getRules()
	{
		return array(
			'cates' => array(
				'UserId' => array('name' => 'UserId', 'require' => true, 'min' => 1, 'desc' => 'user id'),
			),
			
			'notesByKeys' => array(
				'UserId' => array('name' => 'UserId', 'require' => true, 'min' => 1, 'desc' => 'user id'),
				'key' => array('name' => 'keys', 'require' => true, 'min' => 1, 'max' => 50, 'desc' => '关键字'),
			),
			'notesByCate' => array(
				'UserId' => array('name' => 'UserId', 'require' => true, 'min' => 1, 'desc' => 'user id'),
				'cateid'   => array('name' => 'cateid', 'require' => true, 'desc' => '分类Id'),
				'Number'  => array('name' => 'Number', 'default' => 5, 'desc' => '需要的数量'),
        'Page'=>array('name' => 'Page', 'default' => 1, 'desc' => '题目页数')
      ),
			'add' => array(
				'Headline'     => array('name' => 'title', 'desc' => '笔记标题'),
				'NoteCategoryId' => array('name' => 'cateid', 'desc' => '笔记分类id'),
				'Content'      => array('name' => 'content', 'desc' => '笔记内容'),
			),
			'update' => array(
				'Id'           => array('name' => 'Id', 'desc' => '笔记id'),
				'Headline'     => array('name' => 'title', 'desc' => '笔记标题'),
				'NoteCategoryId' => array('name' => 'cateid', 'desc' => '笔记分类id'),
				'Content'      => array('name' => 'content', 'desc' => '笔记内容'),
			),
			'delete' => array(
				'Userid'  => array('name' => 'UserId','require' => true, 'desc' => '用户id'),
				'Nid'  => array('name' => 'Id', 'require' => true,'desc' => '笔记id'),
			),
			
		);
	}

	/**
     * 得到所有笔记分类信息
     */
	public function cates()
	{
		$uid=$this->UserId;
		$mc = new ModelNoteCategory();
		$re = $mc->getNotesCategoryByUserId($uid); //用户Id 需修改
		return MyStandard::gReturn(0, $re);
	}

	/**
   * 根据关键字搜索用户笔记
   */
	public function notesByKeys()
	{
		$dn = new DomainNote();
		$uid=$this->UserId;
		$re = $dn->getNotesByKeywords($uid, $this->key);

		return MyStandard::gReturn(0, $re);
	}


  // /**
	//  * 根据用户分类得到所有notes
	//  * 
	//  * @return data(
	//  * 	notes  当前页笔记
	//  * 	pages  当前界面分页的下标显示格式数组
	//  * )
	//  */
// public function notesByCate()
	// {
	// 	$dn = new DomainNote();
	// 	$uid=$this->UserId;
	// 	$singlePage = $this->num;
	// 	//分页
	// 	$re = $dn->getNotesByCateId($uid, $this->cateid);

	// 	/* ------  获取当前界面页标  ------ */
	// 	$currpages = array(); // 当前展示在界面中的页标
	// 	$count = count($re); // 数据库中查询的总记录数
	// 	$pages = ceil($count * (1.0) / $singlePage); // 获得向上取整的分页总数量
	// 	$curr  = $this->currPage; // 当前页
	// 	$currpages = MyStandard::getPage($pages, $curr); // 第三个参数可不用

	// 	/* ------  获取当前页的数据  ------ */
	// 	$beginNote = $singlePage * ($curr - 1) + 1;  // 开始查询的记录的逻辑位置
	// 	$length   = $singlePage;  // 查询的长度
	// 	$notes = $dn->getByLimit($beginNote, $length);

	// 	$data = array(
	// 		'notes' => $notes,
	// 		'pages' => $currpages,
	// 	);

	// 	return MyStandard::gReturn(0, $data);
// }

	
	/**
	 * 根据用户分类得到所有notes
	 */
	public function notesByCate()
	{
		$dn = new DomainNote();
		$uid=$this->UserId;
		$num = $this->Number;
		$page=$this->Page;
		//分页
		$re = $dn->getNotesByCateId($this->cateid,$page,$num);

		return MyStandard::gReturn(0, $re);
	}
	/**
	 * 添加一条笔记
	 */
	public function add()
	{
		$data = array(
			'Headline'     => $this->Headline,
			'NoteCategoryId' => $this->NoteCategoryId,
			'Content'      => $this->Content,
		);

		$domain = new DomainNote();
		$result = $domain->add($data);
		return MyStandard::gReturn(0, $result,'添加成功');
	}

	/**
	 * 更新一条笔记
	 */
	public function update()
	{
		$data = array(
			'Id'           => $this->Id,
			'Headline'     => $this->Headline,
			'NoteCategory' => $this->NoteCategory,
			'Content'      => $this->Content,
		);

		$domain = new DomainNote();
		$result = $domain->update($data);
		return MyStandard::gReturn(0, $result,'更新成功');
	}
	/**
	 * 删除一条笔记
	 */
	public function delete()
	{
		$uid=$this->Userid;
		$nid = $this->Nid;
		$domain = new DomainNote();
		$result = $domain->delete($nid);
		return MyStandard::gReturn(0, $result,'删除成功');
	}
}
