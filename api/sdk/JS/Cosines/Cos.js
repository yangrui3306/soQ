/*jshint esversion: 6 */
const mathjs = require("mathjs");
const HashMap = require("hashmap");

/**
 * 合并数组并去重
 * @param {数组} arr1 
 * @param {数组} arr2 
 */
function uniqueArr(arr1, arr2) {
  //合并两个数组
  arr1.push(...arr2); //...为将数组转换为用逗号分隔的参数序列，例如[1,2,3] => 1,2,3
  //去重
  return Array.from(new Set(arr1)); //let arr3 = [...new Set(arr1)]
}

/**
 * 得到 词集
 * @param {} arrya 
 * @param {*} arryb
 */
function getWordSet(arrya, arryb) {
  keys = uniqueArr(arrya.Keys, arryb.Keys);

  arrya.Keys = keys;
  for (i = a.Weight.length; i < a.Keys.length; i++) arrya.Weight[i] = 0; //合并a

  temp = {
    Keys: [...keys],
    Weight: []
  };

  for (i = 0; i < keys.length; i++) {
    for (j = 0; j < arryb.Keys.length; j++) {
      if (keys[i] == arryb.Keys[j]) {
        temp.Weight[i] = arryb.Weight[j];
        break;
      }
    }
    if (j == arryb.Keys.length) {
      temp.Weight[i] = 0;
    }
  } ////合并b
  arryb = temp;
  return [arrya, arryb];
}

/**
 * 余弦定理计算
 * @param {} a 
 * @param {*} b 
 */
function calculate(a,b)
{
  let molecule=0.0,denominator=0.0,tempa=0,tempb=0;
  for(i=0;i<a.Keys.length;i++)
  {
    molecule+=(a.Keys[i]*b.Keys[i]);
    tempa+=(a.Keys[i]*a.Keys[i]);
    tempb+=(b.Keys[i]*b.Keys[i]);
  }
  denominator=tempa*tempb;
  if(denominator==0) return 0;
  return molecule/denominator;
}
/**
 * 运行入口,传入两个数组，数组内keys元素不能重复
 * @param {Keys:{1,2...},Weight:{1.0,2.0..}} a 
 * @param {*} b 
 */
function run(a, b) {
  let arry = getWordSet(a, b);

  a = arry[0];
  b = arry[1];

}


let a = {
  Keys: [1, 2, 5],
  Weight: [1.7, 1.2, 0.1]
};

let b = {
  Keys: [3, 2, 4, 5],
  Weight: [2.8, 1.1, 0.9, 0.2]
};

run(a, b);