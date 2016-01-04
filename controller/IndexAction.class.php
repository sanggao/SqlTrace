<?php 
class IndexAction extends Action{

	public function index(){
		$db = new mysql();
		$status = $db->getRow("SHOW VARIABLES LIKE 'general_log'");
		$trace = array();

		if($status['Value'] == 'ON'){
			$trace = $this->getTrace();
		}
		
		$this->display(array('sql'=>$trace,'status'=>$status));
	}

	public function startTrace(){
		if(!file_exists('./runtime/trace.log')){
			$this->touchFile();
		}
		$db = new mysql();
		$db->query("SET GLOBAL general_log_file = '".TRACE_PATH."'");
		$db->query('SET GLOBAL general_log = 1');
	}

	private function touchFile(){
		$fp=@fopen('./runtime/trace.log', "w+");
		if( !is_writable('./runtime/trace.log') ){
			  die("log文件:trace.log不可写，请检查！ 或尝试删除log文件 , 重启mysql");
		}else{
			fwrite($fp,"\r\n");
		}
		@fclose($fp); 
	}

	public function closeTrace(){
		$db = new mysql();
		$db->query('SET GLOBAL general_log = 0');
		
	}

	public function clearTrace(){
		$fp=@fopen('./runtime/trace.log', "w+"); //打开文件指针，创建文件
		if( !is_writable('./runtime/trace.log') ){
			  die("log文件:trace.log不可写，请检查！ 或尝试删除log文件 , 重启mysql");
		}else{
			fwrite($fp, "\r\n");
		}
		@fclose($fp);  
	}

	public function refershTrace(){
		$trace = $this->getTrace();
		$str = '';
		if($trace){
			foreach ($trace as $key => $value) {
				if(strpos($value, 'Quit') !== false && strpos($value, 'Connect') !== false && strpos($value, '@') !==false){
					$tmp = explode('Quit', $value);
					$str .= '<p class="bg-warning tooltip-options" data-toggle="modal" data-target="#myModal"><a href="#"  data-toggle="tooltip" title="点击查看详情">'.$tmp[0].'</a></p><p class="bg-success tooltip-options" data-toggle="modal">
							<a href="#"  data-toggle="tooltip" title="新的链接">'.$tmp[1].'</a></p>';
				}else{
					$str .= '<p class="bg-warning tooltip-options" data-toggle="modal" data-target="#myModal"><a href="#"  data-toggle="tooltip" title="点击查看详情">'.$value.'</a></p>';
				}
				
			}
		}

		echo $str;
		
	}

	private function getTrace(){
		
		$str = file_get_contents(TRACE_PATH);
		$str = preg_replace('/[ ]*(\d+)[ ]Query/','Query', $str);
		$arr = explode('Query', $str);
		return $arr;
	}

	public function getSqlRes(){
		if(isset($_POST['sql']) && !empty($_POST['sql'])){
			$sql = trim($_POST['sql']);
			if(strlen($sql) < 5){
				exit('sql错误');
			}

			$db = new mysql();
			$res = $db->getAll($sql);
			p($res);
		}
	
	}

}