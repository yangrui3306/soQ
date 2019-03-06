# 关键字提取算法

## TF-IDF
>TF-IDF（term frequency–inverse document frequency）是一种用于资讯检索与资讯探勘的常用加权技术。TF-IDF是一种统计方法，用以评估一字词对于一个文件集或一个语料库中的其中一份文件的重要程度。字词的重要性随着它在文件中出现的次数成正比增加，但同时会随着它在语料库中出现的频率成反比下降。TF-IDF加权的各种形式常被搜寻引擎应用，作为文件与用户查询之间相关程度的度量或评级。除了TF-IDF以外，因特网上的搜寻引擎还会使用基于连结分析的评级方法，以确定文件在搜寻结果中出现的顺序。

## 数据库设置

1. **数据库包括 Id Word Weight**
* 其中Word为关键字，Weight为其中权重。举例：关键字“方程”的权重应该比“二元一次”小。

2. **存储题目对应的关键字使用权重策略**
* 题目中某关键字出现频率应与其权重成正比,与其关键字对应权重成反比。举例：“这是一道二元一次方程的题目，该方程为···”,其中“方程”出现两次，“二元一次”出现一次，但由于“二元一次”的关键字权重比“方程”大，所以对于该题二元一次为较重要关键字。

# 推荐算法
## 推荐算法策略
1. **由题目推荐类似题目：基于内容推荐算法**
* 根据题目推荐类似题目时推荐重要顺序应为：分类、相应关键字、热度、题目相似度。
学生想要得到某题目对应的相似题目练习时，应根据其分类进行初步筛选，当然我们需要剔除学生已经做过的题目，然后进行关键字匹配题目。根据某一题目中某一关键字所对应的比重去匹配比重类似的题目，而且对于题目的收藏量、点赞量、错题整理量进行统计增加相应匹配权重加成，筛选出大于指定数量的题目。然后根据题目相似度进行匹配，获得较为相似。
2. **根据用户推荐类似题目：基于用户行为分析**
* 根据学生最近的搜索题目，以及学生的错题整理，点赞题目的详情进行学生行为分析。对学生最近学习内容，学生弱课提供预测和数据统计，对搜索、点赞、收藏、错题整理等行为进行权值分配并推送相应题目。
* 根据用户某些行为涉及到的最近的题目，以及停留时间等参数，提取关键字并汇总，并根据相应操作属性对关键字权值进行加权，并根据相应关键字权值匹配搜索相应题目。
>其中加权公式为：Weight=keyWeight*Type

* 整理行为权值为2.0 收藏行为为：1.5 点赞行为为：1.2  搜索行为为 1.0。
由于整理错题行为更能体现学生最近学习内容，所以权值较大，而搜索题目行为并不能更好反应学生最近学习内容。