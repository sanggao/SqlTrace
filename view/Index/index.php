<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>PHP SqlTrace</title>

	<!-- 新 Bootstrap 核心 CSS 文件 -->
	<link rel="stylesheet" href="./view/common/css/bootstrap.min.css">
	<!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
	<script src="./view/common/js/jquery.min.js"></script>
	<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
	<script src="./view/common/js/bootstrap.min.js"></script>
	<style type="text/css">
		body{height:100%;background: #ccc;}
		.container{padding-top: 30px;}
		.control button{margin-bottom: 10px;}
		.bg-warning{padding: 5px;font-size: 16px;}
		.modal-body{word-break:break-all;}
		.main-l{position: absolute;right: 0px;top:30px;}
	</style>
</head>
<body>
	<div class="container">
		<div class="row">
		  <div class="col-md-3 main-l">
		  	 <div class="panel panel-primary">
			   <div class="panel-heading">
			      <h3 class="panel-title">控制台</h3>
			   </div>
			   <div class="panel-body control">
			   	  <?php if($status['Value'] == 'ON'): ?>
			   	  	<button type="button" id="start" class="btn btn-info btn-block" disabled="disabled">已开启</button>
			   	  	<button type="button" id="stop" class="btn btn-danger btn-block">停止记录</button>
			   	  	<?php else:?>
					 <button type="button" id="start" class="btn btn-info btn-block">开启记录</button>
			      	<button type="button" id="stop" class="btn btn-danger btn-block" disabled="disabled">已停止</button>
			   	  <?php  endif; ?>
			      <button type="button" class="btn btn-warning btn-block" id="clear">清空记录</button>
			      <button type="button" class="btn btn-success btn-block" id="refersh">刷新记录</button>
			   </div>
			</div>
		  </div>
		  <div class="col-md-9 main-r">
		  		<div class="panel panel-info">
				   <div class="panel-heading">
				      <h3 class="panel-title">sql流程</h3>
				   </div>
				  <div class="panel-body" id="sql-trace">
				  	<?php foreach($sql as $val):  ?>
				  		<?php  
				  		  if(strpos($val, 'Quit') !== false && strpos($val, 'Connect') !== false && strpos($val, '@') !==false){
				  		  	$tmp = explode('Quit', $val);
				  		  	echo '<p class="bg-warning tooltip-options" data-toggle="modal" data-target="#myModal">
							<a href="#"  data-toggle="tooltip" title="点击查看详情">'.$tmp[0].'</a></p><p class="bg-success tooltip-options" data-toggle="modal">
							<a href="#"  data-toggle="tooltip" title="新的链接">'.$tmp[1].'</a></p>';
				  		  }else{
				  		  	echo '<p class="bg-warning tooltip-options" data-toggle="modal" data-target="#myModal">
							<a href="#"  data-toggle="tooltip" title="点击查看详情">'.$val.'</a></p>';
				  		  }

				  		?>
				  	<?php endforeach; ?>
				  </div>
				</div>
		  </div>
		  <!-- 模态框（Modal） -->
			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" 
			   aria-labelledby="myModalLabel" aria-hidden="true">
			   <div class="modal-dialog">
			      <div class="modal-content">
			         <div class="modal-header">
			            <button type="button" class="close" data-dismiss="modal" 
			               aria-hidden="true">×
			            </button>
			            <h4 class="modal-title" id="myModalLabel">
			               SQL详情
			            </h4>
			         </div>
			         <div class="modal-body">
			            
			         </div>
			         <div class="modal-footer">
			            <button type="button" class="btn btn-default" 
			               data-dismiss="modal">
			               关闭
			            </button>
			            <button type="button" class="btn btn-primary" id="run-sql">
			               运行sql
			            </button>
			         </div>
			      </div><!-- /.modal-content -->
			   </div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
		</div>
	</div>
</body>
</html>
<script type="text/javascript">
 $(function () { $('.tooltip-show').tooltip('show');});
      $(function () { $('.tooltip-hide').tooltip('hide');});
      $(function () { $('.tooltip-destroy').tooltip('destroy');});
      $(function () { $('.tooltip-toggle').tooltip('toggle');});
      $(function () { $(".tooltip-options a").tooltip({html : true });
      });


function bindControlEvent(){
	$("#start").click(function(){
		$(this).text('开启中...').attr('disabled',true);
		var me = $(this);
		$.get('index.php?c=index&m=startTrace',function(data){
			me.text('已开启')
			$("#stop").text('停止记录').removeAttr('disabled');
		})

	})
	$("#stop").click(function(){
		$(this).text('停止中...').attr('disabled',true);
		var me = $(this);
		$.get('index.php?c=index&m=closeTrace',function(data){
			me.text('已停止')
			$("#start").text('开启记录').removeAttr('disabled');
		})
	})

	$("#clear").click(function(){
		var me = $(this);
		me.attr('disabled',true);
		$.get('index.php?c=index&m=clearTrace',function(data){
			me.removeAttr('disabled');
			$("#sql-trace").html('');
		})
	})
	$("#refersh").click(function(){
		var me = $(this);
		me.attr('disabled',true);
		$.get('index.php?c=index&m=refershTrace',function(data){
			me.removeAttr('disabled');
			$("#sql-trace").html(data);
		})
	})

	$(window).scroll(function(){
		var scrollTop = $(this).scrollTop()+30;
		$('.main-l').stop().animate({top:scrollTop+'px'});
	})
}

function bindSqlEvent(){
	$("#sql-trace").on('click','.bg-warning',function(){
		var sql = $(this).find('a').text();
		$(".modal-body").text(sql).attr('contentEditable',true);

	})

	$("#run-sql").click(function(){
		var me = $(this);
		me.attr('disabled','disabled');
		var sql = $.trim($(".modal-body").text());
		var data = {'sql':sql}
		$("#myModalLabel").text("请稍后,正在返回结果...");
		$.post('index.php?c=index&m=getSqlRes',data,function(res){
			$(".modal-body").attr('contentEditable',false).html(res);
			$("#myModalLabel").text("结果详情");
			me.removeAttr('disabled');
		})
	})
}
$(document).ready(function(){
	bindControlEvent();
	bindSqlEvent();

})


	
</script>
