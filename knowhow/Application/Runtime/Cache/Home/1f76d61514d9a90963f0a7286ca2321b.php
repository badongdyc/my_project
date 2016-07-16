<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Insert title here</title>
</head>
<body>
	<form action="<?php echo U('Test/insertAns');?>" method='post'>
		<textarea rows="5" cols="20" name='area'></textarea>
		<input type='submit'/>
	</form>
</body>
</html>