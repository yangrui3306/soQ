const HashMap = require("hashmap")

let lista=new HashMap()
let listb=[]
lista.set(1,6)
lista.set(2,4)
lista.set(3,5)
lista.forEach(function(value,key){
  listb.push(value)
})
console.log(listb)