<?php
/**
 * 支付宝支付
 */

return [
        //应用ID,您的APPID。
        'app_id' => "2018010201511185",

        //商户私钥, 请把生成的私钥文件中字符串拷贝在此
        'merchant_private_key' => "MIIEowIBAAKCAQEAu0h1VZELvN7e9KoEXY2FX6Er9PygP55UfowOYUPyLwRORaWMx+Sb1ivLVKhPUk0BDHeTCTZPBiHIqQzIUbSOUKdhmEr9Q16nwvPZjGRIPuXJDrJB79lbEX3ico1XcYXRuTonoUTkiVTGbl41pySWmXXb5vKhhd7T1av1Gqi4xWr6W8/Y2h3jcqskh7WcVjPlP9fvivvTZDPja5DCbxkzsxeVgIL4YGPEK8KfLalxpr/RaDkGelhsZ/mefbs1Fq4p07ysQ76plpsQihJTBvIPL4L6Rp4Me/yHUNT3AUzo7r7MW+P6QOsLA0W/vIg7OOJsXhFlPnvbeydiDxr6T0yf6wIDAQABAoIBADq191qQsu5Nq/VlSyMM14/oFcCiZZYsC270dxmU1Fpa7jK1OFH938CfUAnFDcDONRL0dVrA9LsGYkJkqHO8t5SfNGAqugShqtPZ4Aw7784P+RyrUzJeoEb2gMPKfWwwOFPp4DyVPVO0CNTWodk5BiErqnlW/L3b4eqtEpR96haw7VkZzHKBUaLIAZb/eEdssi6G64rtb3Pi/LRinPA85SC3Gj9qmCEfQJhr6XRha5q3C9otEvgsL+EPUOtKmPN7BpRMuisg2TOTf2PlKrjGUhCpUUSgTNKOXtZNZiCQ4J0FBf6iQUHl5gufkLEneA/uc8gDuYpvxDTgkN/AyzSOx5ECgYEA+CAWIdNT+3lqJt6NPuuh5XYyzqGg4St2vnwrI2BAestmSqlApX0ucTgeWVMMCfMGsVPF4cZBySKzvBLCa/Bkg0KiNmDejGrlAlV4IgdaLNl4hb0/1S+VGLRxF1zOeFlaSWnCO6aiaU2UDqxhiO7w1c5KLz1K0y+JVgHTco/rRdMCgYEAwToNzcAnPuJRkjOnpR1z7992RsF+mm8JOG5dcXeQ+G7sR9TX/e+KFWOBXDeCVCZgSaUqmMgvaJQrjkb9911J1jlXL9kLP3gVw7/XRv3pHaXoVuGkrJ2LSdG6+rHTl5+aLbIYLR/+sSqqTHX9CdfslSXVRZpmxC2z4YIGiXzXdokCgYB8o9maSkcApv/84ITBW6pq0tI43BYtpiCzFTqyPZKGXJBY5uaTeuuOcitEoaQFh3AQOc3IWUgImocA7cd+YzaKfTlw6X5BuRyq7HY5Wcohh2i0fdmH5KwXyeSZYMRSzke9YwPv5Qfmr9K1AEDKgYS3UjYPvtFMfq+VZbtq7AK6gwKBgQCUe4EJ/8Povc9DEvCboENsLALeomggUnz6YUzrGZ9MWSi2lieYWk942isY6wr6kTbiwMo393T+3wWNYp5cTNKljRCjkxzim+vVrYDHa+yMPOaKhQCfKL1Vb5ZIeQgI9rtqq/WKD5EPIaMV+IEG5GdHwXfe8aRQ0nHtM5bEZivISQKBgHtQ4PzVYSrDuO3NZ+zmvnrnAUPwejFzuTUxMKzZCLCedL3jXhbBryMpfDUfwuJz0j+dJGHAQplWOFRYaYNRAzERuVg5kzcZzNlPyjntTJyQ9gjyXMSAas/c7ndFX9Jh6G1FnYoQA2ILnkSmPuiAVUwfVhfzORuQlDjm2MTd2hGE",

        //异步通知地址
        'notify_url' => "home/notify.html",

        //同步跳转
        'return_url' => "home/alipayreturn.html",

        //编码格式
        'charset' => "UTF-8",

        //签名方式
        'sign_type' => "RSA2",

        //支付宝网关
        'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

        //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
        'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAnFadTsfrrFABimGZIxFolAK05Vawd/1JIiPGqNP+KGuCeDxMYYQay5xXa8Z/+FOK2WFldecqA6lld/EelYxMqR84E8D98kUZiEy4ZspA9xYyLapkZSBVtiDPoLoqj+QKRpmVbNFKJ07D9fxcLl7PCud+OHeL0osUr7MplFPlLBAJn9PCvq8Udo7Rl1RvxZkSTMvhm9pJnvFcSNCdlCh81LI7TV4XmrZtETZKQcbU21xEHOvsPXdrgeVGZYbodbP4rdEuz0/yWkr1Q4eLWQdbwzWtk7xVXts5IsvP8fEI4tQAF+namCsZmv8WLhXQ5T3Shd4JsEyLEkOPcFzyjByD3QIDAQAB",
];
