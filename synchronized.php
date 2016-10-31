<?php
/**
 * Created by PhpStorm.
 * User: xbs530
 * Date: 2016/2/16
 * Time: 15:09
 */

/**
 * 线程同步函数,可以保证并发数据安全
 */
if(!function_exists('synchronized')){
    class synchronized
    {
        private $lock_handle=null;
        private $lock_ready=false;


        public function __construct($scope_name='',$block=true)
        {
            $this->lock_ready=$this->lock_start($scope_name,$block);
        }

        public function run(Closure $callback)
        {
            return call_user_func_array($callback,array($this->lock_ready));
        }

        public function __destruct()
        {
            $this->lock_end();
        }



        private function lock_start($lock_name,$block=true){

            do {
                $file_path = "/tmp/synchronized_{$lock_name}.tmp";

                //死锁处理
                if (is_file($file_path)) {
                    $lock_time = file_get_contents($file_path);
                    $lock_timeout = $lock_time + 60 * 10;
                    if ($lock_time && $lock_timeout < time()) {
                        unlink($file_path);
                    }
                }
                touch($file_path);
                $this->lock_handle = fopen($file_path, 'r+');
                if (!$this->lock_handle) {
                    return false;
                }
                $lock = flock($this->lock_handle, LOCK_EX | LOCK_NB);

                //获得锁
                if($lock){
                    fwrite($this->lock_handle,time());
                    break;

                    //没得锁
                }else{

                    //阻塞模式
                    if($block){
                        $this->lock_end();
                        sleep(10);

                        //非阻塞模式
                    }else{
                        break;
                    }

                }

            }while($block);

            return $lock;
        }

        private function lock_end(){
            flock($this->lock_handle,LOCK_UN);
            fclose($this->lock_handle);
            $this->lock_handle=null;
        }



    }

    /**
     * @param Closure $run
     * @param bool|true $block
     * @return mixed
     */
    function synchronized(Closure $run,$block=true){
        $call_info=debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[1];
        $scope_name=$call_info['class'].'-'.$call_info['function'];
        $s=new synchronized($scope_name,$block);
        return $s->run($run);
    }
}