<?php 
	
	// 1.初始化
	$cn = curl_init();
	
	// 2.链接邀请求的接口地址
	$appid = "wx69a8e6f8662498de";
	$secret = "fc2a855d4e6a9885390cd71e34f2b45c";
	$Url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}"; 
	// 3. 设置传输至
	curl_setopt($cn,CURLOPT_URL,$Url);

	// 4. 以文件流的形式返回
	curl_setopt($cn,CURLOPT_RETURNTRANSFER,1);

	// 5. 执行
	 $data = curl_exec($cn);
	// 6. 关闭 
	curl_close($cn);

	// echo $data;
	$Res = json_decode($data,true);
	var_dump($Res);die;
	$access_token = $Res['access_token'];
	echo $access_token;


	// 
