from PIL import Image
import numpy
import time
from Calculate import skimage_gray_value
from Chart import bar_chart


# 列分割


def AcrCut(img):
    li = list(numpy.sum(numpy.array(img) == 0, axis=0))  # 对每一列进行求和并返回一行元素
    k = 0

    posx = []
    width, height = img.size
    for i in range(len(li)):
        if i > 0 and li[i] >= 5 and li[i - 1] < 5:
            posx.append(i)
            k = k + 1
        if i > 0 and li[i - 1] >= 5 and li[i] < 5:
            posx.append(i)
            k = k + 1

    if len(posx) % 2 == 1:  # 没用的一句话
        posx.append(width)
    lis = []
    try:
        for i in range(len(posx)):  # 遍历寻找起始与结束点，数量不确定，所以用数组遍历
            if i % 2 == 0:
                lis.append(img.crop([posx[i], 0, posx[i + 1], height]))

        return lis

    except:

        print("Error in AcrCut")


# 行切割标准
def HorCutCheck(i, prei, dis=5):
    return i > prei + dis  # 忽略相邻三个像素的点内有分割线


# 计算切割信息
def HorCutDate(cuty,cutt,cutb):
    disy = []
    for i in range(1, len(cuty)):
        disy.append(cuty[i] - cuty[i - 1])

    # bar_chart(range(0, len(disy)), disy)  # 图表

    median = numpy.median(disy)  # 中值
    median = int(median) >> 1
    tempcuty = []
    tempcutt=[]
    tempcutb=[]
    for i in range(0, len(disy)):
        if median > disy[i]:  # 去除过小的分割
            # print(str(cuty[i]) + " " + str(disy[i]))
            continue
        else:
            tempcuty.append(cuty[i])
            tempcutt.append(cutt[i])
            tempcutb.append(cutb[i])

    tempcuty.append(cuty[len(cuty) - 1])
    cuty = tempcuty

    disy = []
    for i in range(1, len(cuty)):
        disy.append(cuty[i] - cuty[i - 1])

    return tempcutt,cuty, tempcutb,disy


# 行分割
def HorCut(img  # PIL格式
           , yuzhi=0  # 阈值 行和大于该值时不可分割(默认为 width*0.007+1)
           ):
    width, height = img.size
    img_array = numpy.array(img)  # 将img转数组
    if yuzhi == 0:
        yuzhi = width * 0.005 + 1

    li = list(numpy.sum(img_array == 0, axis=1))  # 将其转换为array并求和
    k = 1
    posy = [0]
    cuty = []
    cutt = [0]  # 空白区域的top
    cutb = []  # 空白区域的bottom
    for i in range(1,len(li)):
        if li[i] <= yuzhi:
            if HorCutCheck(i, posy[k - 1]):
                # img_array[i] = numpy.zeros(width, dtype=bool)  # 全部赋值为1
                cutt.append(i)
                cutb.append(posy[k - 1])
            posy.append(i)
            k = k + 1

    cutb.append(height)
    for i in range(len(cutt)):
        cuty.append(int((cutt[i] + cutb[i]) / 2))  # 记录切点位置 空白区域中间部分

    cutt,cuty,cutb, disy = HorCutDate(cuty,cutt,cutb)

    ''' ----- 显示 调试部分 ----  '''
    # bar_chart(range(0, len(disy)), disy)  # 图表
    for i in range(len(cuty)):
        img_array[cuty[i]] = numpy.zeros(width, dtype=bool)  # 全部赋值为1
    img_array = numpy.uint8(img_array)  # 转类型
    img_array[img_array > 0] = 255  # 将1转为255
    Image.fromarray(img_array).show()  # 返回图片

    return {"cuty": cuty, "disy": disy, "posy": posy,"cutt":cutt,"cutb":cutb}


# 图像二值化
def binarize(img, threshold=120):
    img = img.convert('L')

    table = []
    for i in range(256):
        if i > threshold:
            table.append(1)
        else:
            table.append(0)
    bin_img = img.point(table, '1')  # 根据table表进行区分

    return bin_img


def pre_run(imgpath):

    img = Image.open(imgpath)
    # print(tesseract_ocr(img)) # 文字识别
    # contrast_img = ImageEnhance.Contrast(img).enhance(2.0) #对比度增加两倍

    gray_value = skimage_gray_value(imgpath)  # 利用skimage获取二值化阈值 注：耗时较大

    # print("原图灰度值设定：" + str(gray_value))
    re_img = binarize(img, gray_value)

    data = HorCut(re_img)
    data["image"] = img
    data["binarize_image"] = re_img
    # print(data)

    return data

# pre_run("image/test(1).jpg")
# img=Image.open("image/test(2).jpg")
# binarize(img,130).show()

# img.rotate(270,expand=True).save("image/test6_rotate.jpg") # 顺时针旋转270度
# img.show()
# print(img.size)

# re_img.show()
# print(HorCut(re_img).show())
# HorCut(re_img).show()
# print(tesseract_ocr(re_img))
