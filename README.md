# easyplottor

## 使用方法

...

1.你需要一个可以访问的webserver
2.配置webserver使得easyplotter可以被外部http正常访问
3.post数据到easyplotter
    curl 'http://server:port/easyplotter/api/set.php?name=XXX' --data-binary @test.dat
    发送成功之后会返回一个图表对应的key:KKK
4.查询图表
    在浏览器打开：http://server:port/easyplotter/api/get.php?key=KKK

...

