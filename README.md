# PHP synchronized
用PHP实现的类似java中的synchronized关键字，保证线程安全，同步代码执行

# PHP版本要求：>5.4

# 参数说明：
synchronized函数有两个参数

  第一个是要执行的闭包函数代码

  第二个参数是是否阻塞其他线程（默认为true）
### 注意：如果设置为非阻塞，那么不会阻塞任何线程的执行，但是闭包函数的$ready参数就会在线程未同步的情况下返回false

# 用法如下：
synchronized(function($ready){

    //线程未同步
    if(!$ready){

        //在这里做一些查询或者返回错误信息操作吧。。。


    //线程已同步
    }else{

        //放心的在这里进行数据增删改查操作吧。。。

    }



});

# 注意：
暂时只支持linux，如果要用于windows请把代码中/tmp目录改为windows下的临时目录（一般是：C:\Windows\Temp）。

# 交流QQ：987188548
