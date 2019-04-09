#-*- coding:utf-8 -*-
#gaoyiping (iam@gaoyiping.com) 2017-02-18

import PhalApiClient;

print('-' * 20)
print('Request: 1')
result = PhalApiClient.PhalApiClient('http://127.0.0.1/', 'App.Keyword.GetList', {'Page': '1','Number':'5'}, 3)
print('head', result['info'])
print('state', result['state'])
print('result', result['data'])
print('-' * 20)
# print('Request: 2')
# result = PhalApiClient.PhalApiClient('http://demo.phalapi.net/', 'User.GetBaseInfo', {'username': 'dogstar'})
# print('head', result['info'])
# print('state', result['state'])
# print('result', result['data'])
# print('-' * 20)
# print('Request: 3')
# result = PhalApiClient.PhalApiClient('http://demo.phalapi.net/')
# print('head', result['info'])
# print('state', result['state'])
# print('result', result['data'])
# print('-' * 20)
# print('Request: 4 (illegal request)')
# result = PhalApiClient.PhalApiClient(1, 2, 3, 4)