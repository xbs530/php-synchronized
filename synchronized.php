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


        public function __construct($scope_name='')
        {
            $this->lock_ready=$this->lock_start($scope_name);
        }

        public function run(Closure $callback)
        {
            return call_user_func_array($callback,array($this->lock_ready));
        }

        public function __destruct()
        {
            $this->lock_end();
        }



        private function lock_start($lock_name){
            $file_path="/tmp/synchronized_{$lock_name}.tmp";
            touch($file_path);
            $this->lock_handle=fopen($file_path,'w');
            if(!$this->lock_handle){
                return false;
            }
            return flock($this->lock_handle,LOCK_EX);
        }

        private function lock_end(){
            flock($this->lock_handle,LOCK_UN);
            fclose($this->lock_handle);
            $this->lock_handle=null;
        }



    }

    /**
     * @param Closure $run
     * @return mixed
     */
    function synchronized(Closure $run){
        $call_info=debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[1];
        $scope_name=$call_info['class'].'-'.$call_info['function'];
        $s=new synchronized($scope_name);
        return $s->run($run);
    }
}