/*jshint esversion: 6 */
const mathjs = require("mathjs");
const HashMap = require("hashmap");


class pushalgorithm {
  /**
   * 构造函数 
   */
  constructor() {
    this.map = new HashMap();
    this.all_listqid = []; //问题列表 自动生成添加 优化算法

  }

  handleQuestrionMap(qid) {
    let i;
    for (i = 0; i < this.all_listqid.length; i++) {
      if (this.all_listqid[i] == qid) {
        break;
      }
    }
    if (i == this.all_listqid.length) {
      this.all_listqid.push(qid);
    }
    return this;
  }

  /**
   *添加数据
   * @param {包含用户id对qid感兴趣为weight} data 
   */
  addData(data) {
    this.handleQuestrionMap(data.QuestionId);
    if (this.getUserById(data.UserId) == null) {
      let questionMapone = new HashMap();
      questionMapone.set(data.QuestionId, data.Interestingness);
      let dataone = {
        uid: data.QuestionId,
        questionMapone: questionMapone,
        totalweight: data.Interestingness
      }
      this.map.set(data.UserId, dataone);
    } else {
      let dataone = this.getUserById(data.UserId);
      dataone.questionMapone.set(data.QuestionId, data.Interestingness);
      dataone.totalweight += data.Interestingness;
    }
    return this;
  }
  /**
   *添加数据列表
   * @param {*} datalist
   */
  addDataList(datalist) {
    for (var i = 0; i < datalist.length; i++) {
      this.addData(datalist[i]);
    }
    return this;
  }
  /**
   * 得到map对象的keyList
   */
  GetKeysListByMap(sourceMap) {
    let listkeys = [];
    sourceMap.forEach(function (value, key) {
      listkeys.push(key);
    });
    return listkeys;
  }
  /**
   * 得到map对象的ValuList
   */
  GetValuesListByMap(sourceMap) {
    let listvalues = [];
    sourceMap.forEach(function (value, key) {
      listvalues.push(value);
    });
    return listvalues;
  }
  /**
   * 得到用户对象   （用户不存在null）
   * @param {用户id}} id 
   */
  getUserById(id) {
    return this.map.get(id);
  }
  /**
   * 得到用户U，V的相同感兴趣问题集合  （用户不存在列表为空）
   * @param {用户id} uid 
   * @param {用户id} vid 
   */
  getDegreeOfIQId(uid, vid) {
    let u = this.getUserById(uid),
      v = this.getUserById(vid);
    let listqid = [];
    if (u == null || v == null) {
      return listqid;
    }
    u.questionMapone.forEach(function (value, key) {
      if (v.questionMapone.get(key) != null) {
        listqid.push(key);
      }
    });
    return listqid;
  }
  /**
   * 得到用户的感兴趣度 （用户不合法为null）
   * @param {用户id} uid 
   * @param {问题id} qid 
   */
  getUserInterestByid(uid, qid) {
    let user = this.getUserById(uid);
    return user == null ? null : (user.questionMapone.get(qid) == null ? 0 : user.questionMapone.get(qid)) ;
    //判断是否合法否则返回0
  }
  /**
   * 得到两个用户的相似度 （用户不存在为null）
   * @param {用户id} uid 
   * @param {用户id} vid 
   */
  simUandV(uid, vid) {
    let u = this.getUserById(uid),
      v = this.getUserById(vid);
    if (u == null || v == null) {
      return null;
    }
    let avgRu = u.totalweight / u.questionMapone.size,
      avgRv = v.totalweight / v.questionMapone.size;
    let listqid = this.getDegreeOfIQId(uid, vid);
    let sim = 0.0; //结果
    let numberator = 0.0; //sim函数分子
    let denominatoru = 0.0,
      denominatorv = 0.0,
      denominator = 0.0; //sim函数分母
    //sim算法http://upy.iimt.me/WX20181007-144838.png
    try {
      if (listqid.length == 0) {
        return 0;
      }
      for (var i = 0; i < listqid.length; i++) {
        let partu = (this.getUserInterestByid(uid, listqid[i]) - avgRu),
          partv = (this.getUserInterestByid(vid, listqid[i]) - avgRv);

        numberator += (partu * partv); //求sim函数分子
        denominatoru += (partu * partu);
        denominatorv += (partv * partv);
      }

      denominator = mathjs.sqrt(denominatoru) * mathjs.sqrt(denominatorv);
      sim = numberator / denominator;
    } catch (error) {
      return 0;
    }
    return sim;
  }
  /**
   * 获取两个用户相似度 
   * @param {用户id} uid 
   * @param {用户id} vid 
   */
  getSimUandV(uid, vid) {
    let u = this.getUserById(uid),
      v = this.getUserById(vid);
    if (u == null || v == null) {
      return null;
    }
    if (u.simMapone != null) {
      return u.simMapone.get(vid) == null ? 0 : u.simMapone.get(vid);
    }
    if (v.simMapone != null) {
      return v.simMapone.get(uid) == null ? 0 : v.simMapone.get(uid);
    }
    return 0;
  }
  /**
   * 建立相似度map
   */
  setSimMapMatrix() {
    //获取id列表
    let listid = this.GetKeysListByMap(this.map);

    for (var i = 0; i < listid.length; i++) {
      for (var j = i + 1; j < listid.length; j++) {
        //判断用户 i j 相似度并写入simMap
        let sim = this.simUandV(listid[i], listid[j]); //获取相似度
        let user = this.getUserById(listid[i]);
        if (user.simMapone == null) {
          let simMapone = new HashMap();
          simMapone.set(listid[j], sim);
          user.simMapone = simMapone;
        } else {
          user.simMapone.set(listid[j], sim);
        }
      }
    }
    return this;
  }
  /**
   * 获取用户对问题的预测感兴趣度
   * @param {用户id} uid 
   * @param {问题id} qid 
   */
  predictionInterestNumber(uid, qid) {
    let listvid = [],
      numberator = 0.0,
      denominator = 0.0; //初始化vid列表 函数分子分母 F-8812B162 http://upy.iimt.me/0703BD6E-408F-4DC7-ABA5F20.png
    let user = this.getUserById(uid);

    try {
      listvid = this.GetKeysListByMap(this.map);

      for (var i = 0; i < listvid.length; i++) {
        if (listvid[i] == uid) {
          continue;
        }
        let Suv = this.getSimUandV(uid, listvid[i]);
        numberator += (this.getUserInterestByid(listvid[i], qid) * Suv);
        denominator += Suv;
      }
    } catch (error) {
      return null;
    }
    return (numberator / denominator)==null?0:(numberator / denominator);
  }
  /**
   * 建立预测map矩阵
   */
  setPredictionMapMatrix() {
    let listUserId = this.GetKeysListByMap(this.map);
    for (var i = 0; i < listUserId.length; i++) {
      let user = this.getUserById(listUserId[i]);
      user.predMapone = new HashMap();
      for (var j = 0; j < this.all_listqid.length; j++) {
        if (user.questionMapone.get(this.all_listqid[j]) == null) {
          let pred = this.predictionInterestNumber(listUserId[i], this.all_listqid[j]);
          pred == null ? "" : user.predMapone.set(this.all_listqid[j], pred);
        }
      }
    }
    return this;
  }
  /**
   * 得到用户对问题的感兴趣度 or 预测感兴趣度（不存在返回null）
   */
  getPredictionNumberById(userid, qid) {
    try {
      let user = this.getUserById(userid);
      
      return user.questionMapone.get(qid) == null ? user.predMapone.get(qid) : user.questionMapone.get(qid);
    } catch (error) {
      return null;
    }
  }
  /**
   * 输出预测感兴趣度信息
   */
  outPutPredictionMatrix() {
    let listUserId = this.GetKeysListByMap(this.map);
    for (var i = 0; i < listUserId.length; i++) {
      console.log(i + " : ");
      // try{
      let user = this.getUserById(listUserId[i]);
      user.predMapone.forEach(function (value, key) {
        console.log("对问题 " + key + " 的感兴趣度为：" + value);
      });
      //}
      //catch(error){
      //  continue
      //}
    }
    return this;
  }
  /**
   * 输出相似度矩阵
   */
  outPutSimMapMatrix() {
    let listUserId = this.GetKeysListByMap(this.map);
    let outlist = [];
    for (var i = 0; i < listUserId.length; i++) {
      let listvid = [];
      for (var j = i + 1; j < listUserId.length; j++) {
        let sim = this.getSimUandV(listUserId[i], listUserId[j]);
        listvid.push(sim);
      }
      outlist.push(listvid);
    }
    console.log(outlist);
    return this;
  }

}



datalist = [];
for (var i = 1; i < 1000; i++) {
  data = {
    UserId: Math.floor(Math.random() * 20 + 1),
    QuestionId: Math.floor(Math.random() * 50 + 1),
    Interestingness: Math.floor(Math.random() * 5 + 1)
  };
  //console.log(data);
  datalist.push(data);
}
let algorithm = new pushalgorithm();
algorithm.addDataList(datalist);
algorithm.setSimMapMatrix();
algorithm.setPredictionMapMatrix().outPutSimMapMatrix().outPutPredictionMatrix();
