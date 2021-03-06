import pytesseract
import urllib
from urllib import parse
from PIL import Image
import time
import requests
import base64
from io import BytesIO

access_token="24.678d847861d01af051aaddbc9c65d352.2592000.1562991590.282335-15879343"
# 文字识别 传入 PIL的Img 和 识别语言

def tesseract_ocr(img, lang='chi_sim'):
    pytesseract.pytesseract.tesseract_cmd = r'E:\Tesseract-OCR\tesseract.exe'

    print(time.time())
    text = pytesseract.image_to_string(img, lang=lang)
    print(time.time())
    return text


def get_baidu_token():
    host = 'https://aip.baidubce.com/oauth/2.0/token?grant_type=client_credentials&client_id=lQlRqLIxzfyYjuACHONrurt2&client_secret=Msy8pGwW6L5jfHX4zg8WRaml97E6dWZU'
    request = urllib.request.Request(host)
    request.add_header('Content-Type', 'application/json; charset=UTF-8')
    response = urllib.request.urlopen(request)
    content = response.read()
    if content:
        print(content)


def baidu_orc(path):
    #access_token = "24.607dbc19903a32f5e7fde1932acf6c38.2592000.1556360828.282335-15879343"  # 自行注册百度云账号，即可获取自己专属的access_token，必须输入！
    # print(time.time())
    with open(path, 'rb') as f:
        image_data = f.read()
        base64_ima = base64.b64encode(image_data)
        # print(time.time())
        data = {
            'image': base64_ima
        }
        headers = {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
        url = "https://aip.baidubce.com/rest/2.0/ocr/v1/general?access_token=" + str(access_token)
        r = requests.post(url, params=headers, data=data).json()
        # print(time.time())
        return r
        # for word in r['words_result']:
        #     yield word['words']
        # 返回一个生成器，可自行修改


# PIL img转base64
def pil_base64(image):
    img_buffer = BytesIO()
    image.save(img_buffer, format='JPEG')
    byte_data = img_buffer.getvalue()
    base64_str = base64.b64encode(byte_data)
    return base64_str


# PIL
def baidu_orc_pil(img):# 较直接使用文件路径慢半秒
    #access_token = "24.607dbc19903a32f5e7fde1932acf6c38.2592000.1556360828.282335-15879343"  # 自行注册百度云账号，即可获取自己专属的access_token，必须输入！
    print(time.time())
    base64_ima = pil_base64(img)
    print(time.time())
    data = {
        'image': base64_ima
    }
    headers = {
        'Content-Type': 'application/x-www-form-urlencoded'
    }
    url = "https://aip.baidubce.com/rest/2.0/ocr/v1/general?access_token=" + str(access_token)
    r = requests.post(url, params=headers, data=data).json()
    print(time.time())
    return r["words_result"]
    # for word in r['words_result']:
    #     yield word['words']
    # 返回一个生成器，可自行修改


# if __name__ == '__main__':
    # path = "image/test(2).jpg"
    # img = Image.open(path)
    # print(baidu_orc_pil(img))
    # print(baidu_orc(path))
    # get_baidu_token()
