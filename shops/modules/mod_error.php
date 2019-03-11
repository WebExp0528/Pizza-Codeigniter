<script type="text/javascript" language="JavaScript" src="<?php echo $site_root;?>common/js/jquery-1.6.2.min.js"></script>
<input type="hidden" value="<?php echo $_SERVER['HTTP_HOST']?>" id="http_request">
<script>
	var Request_url = $("#http_request").val();
	location.href = "http://"+Request_url+"/pizza/";
</script>