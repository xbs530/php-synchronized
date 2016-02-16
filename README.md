# PHP synchronized
用PHP实现的类似java中的synchronized关键字，保证线程安全，同步代码执行

# 用法如下：
synchronized(function($ready){

    //线程同步失败
    if(!$ready){

      //做下处理吧（当然一般是不会失败的）


    //线程同步成功
    }else{

        //包裹你要执行的代码吧。。。

    }



});

# 注意：
暂时只支持linux，如果要用于windows请把代码中/tmp目录改为windows下的临时目录。

# 交流QQ：987188548
