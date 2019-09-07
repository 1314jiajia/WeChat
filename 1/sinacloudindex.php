<?php
	// 设置token
	define('TOKEN','Jojo');

   // 获取服务器传递参数
    function checkSignature()
	{
		// 加密签名
		$signature = $_GET["signature"];
		
		// 时间戳 
		$timestamp = $_GET["timestamp"];

		// 随机数
		$nonce = $_GET["nonce"];

		// 把数据拼装成数组
		$tmpArr = array($timestamp, $nonce,TOKEN);
		
		// 排序
		sort($tmpArr, SORT_STRING);
		
		// 数组转成字符串 
		$tmpStr = implode( $tmpArr );
		
		// 加密
		$tmpStr = sha1( $tmpStr );
 		
 		// 对比服务器和刚刚加密的信息
		if( $signature == $tmpStr ){
			return true;
		}else{
			return false;
		}

	}


		// 调用函数
		if(checkSignature()){
			$echostr = $_GET['echostr'];
			// 是否有值
			if($echostr){
				echo $echostr;
				exit;
			}
		}

	// 文本消息处理
	// 获取微信端发送的消息
		// $data = $HTTP_RAW_POST_DATA;
		$data = file_get_contents('php://input');
		// var_dump($data);die;

		if(!$data){

			echo "不知道原因反正是报错了";
			exit;
		}

	// xml 转换成对象
	$object = simplexml_load_string($data,"SimpleXMLElement",LIBXML_NOCDATA);
	// var_dump($object);die;
	// 获取接收方
	$FromUserName = $object->FromUserName;
	
	// 获取发送方
	$ToUserName = $object->ToUserName;

	// 消息类型
	$MsgType = $object->MsgType;

	// 判断消息类型
	switch ($MsgType) {
		//消息类型 图片类型  图片消息交互
		case 'image':
				
				// 获取客户端发送的消息(当做回复消息)
				$Content = $object->MediaId;
				
				// 微信公众号把处理过的消息直接返回给客户端
				$MsgReply = "<xml>
							  <ToUserName><![CDATA[%s]]></ToUserName>
							  <FromUserName><![CDATA[%s]]></FromUserName>
							  <CreateTime>%s</CreateTime>
							  <MsgType><![CDATA[image]]></MsgType>
							  <Image>
							    <MediaId><![CDATA[%s]]></MediaId>
							  </Image>
							</xml>";
			break;

		//语音消息交互
		case "voice":

				// 获取客户端发送的消息(当做回复消息)
				$Content = $object->MediaId;
			
				// 微信公众号把处理过的消息直接返回给客户端
			
					$MsgReply = "<xml>
								  <ToUserName><![CDATA[%s]]></ToUserName>
								  <FromUserName><![CDATA[%s]]></FromUserName>
								  <CreateTime>%s</CreateTime>
								  <MsgType><![CDATA[voice]]></MsgType>
								  <Voice>
								    <MediaId><![CDATA[%s]]></MediaId>
								  </Voice>
								</xml>";
			break;
		
		
			case "text":
				$Content = $object->Content;
				if($Content == "唯品会"){
					// 拼装数据
					$dataArr = array(array(
									 'Title'=>'唯品会',
								 'Description'=>'卖假货就上唯品会',
								 'PicUrl'=>'http://h2.appsimg.com/a.appsimg.com/upload/merchandise/pdcvis/109950/2019/0518/147/885a58be-7945-42f8-a493-7bcdb41b8d13_420_531_235x297_90.jpg',
								 'Url'=>'https://www.vip.com/'
								));
								
								$dataList = '';
								// 遍历数据
								foreach ($dataArr as $key => $value) {
									$dataList.= "<item>
											      <Title><![CDATA[".$value['Title']."]]></Title>
											      <Description><![CDATA[".$value['Description']."]]></Description>
											      <PicUrl><![CDATA[".$value['PicUrl']."]]></PicUrl>
											      <Url><![CDATA[".$value['Url']."]]></Url>
											    </item>";
								}
									$MsgReply = "<xml>
													  <ToUserName><![CDATA[%s]]></ToUserName>
													  <FromUserName><![CDATA[%s]]></FromUserName>
													  <CreateTime>%s</CreateTime>
													  <MsgType><![CDATA[news]]></MsgType>
													  <ArticleCount>".count($dataArr)."</ArticleCount>
													  <Articles>".$dataList."</Articles>
											</xml>";

				}elseif ($Content == "京东") {
						// 拼装数据
					$dataArr =array(array(
									 'Title'=>'京东',
								 'Description'=>'京东shop',
								 'PicUrl'=>'//img12.360buyimg.com/n1/s350x449_jfs/t6352/203/1269842689/136564/1a3277a7/594e128bN748207f9.jpg!cc_350x449.jpg',
								 'Url'=>'https://www.jd.com/'
								));
								
								$dataList = '';
								// 遍历数据
								foreach ($dataArr as $key => $value) {
									$dataList.= "<item>
											      <Title><![CDATA[".$value['Title']."]]></Title>
											      <Description><![CDATA[".$value['Description']."]]></Description>
											      <PicUrl><![CDATA[".$value['PicUrl']."]]></PicUrl>
											      <Url><![CDATA[".$value['Url']."]]></Url>
											    </item>";
								}
									$MsgReply = "<xml>
													  <ToUserName><![CDATA[%s]]></ToUserName>
													  <FromUserName><![CDATA[%s]]></FromUserName>
													  <CreateTime>%s</CreateTime>
													  <MsgType><![CDATA[news]]></MsgType>
													  <ArticleCount>".count($dataArr)."</ArticleCount>
													  <Articles>".$dataList."</Articles>
											</xml>";

				} elseif($Content == "笑话"){
												// 1.初始化
								$ch = curl_init();

								// 2. 设置抓取url地址
								$url = "http://www.kuitao8.com/api/joke";

								// 3. 抓取方式为get
								curl_setopt($ch,CURLOPT_URL,$url);
								// 返回页面为文件流
								curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

								// 执行
								$data = curl_exec($ch);

								// 关闭资源
								curl_close($ch);

								// 转换为数组
								$res = json_decode($data,true);
								// var_dump($res);die;
								$Content = $res['content'];

							$MsgReply = "<xml>
										  <ToUserName><![CDATA[%s]]></ToUserName>
										  <FromUserName><![CDATA[%s]]></FromUserName>
										  <CreateTime>%s</CreateTime>
										  <MsgType><![CDATA[text]]></MsgType>
										  <Content><![CDATA[%s]]></Content>
									  	 </xml>";


				}else{
					$Content = "没有这个关键词";

					 $MsgReply = "<xml>
						  <ToUserName><![CDATA[%s]]></ToUserName>
						  <FromUserName><![CDATA[%s]]></FromUserName>
						  <CreateTime>%s</CreateTime>
						  <MsgType><![CDATA[text]]></MsgType>
						  <Content><![CDATA[%s]]></Content>
					   </xml>";
				}
			break;
				//关注事件
		case "event":

				// 获取客户端发送的消息(当做回复消息)
				$Event = $object->Event;
				switch ($Event) {
					case 'subscribe':
						$Content = "Welcome";

					 $MsgReply = "<xml>
									  <ToUserName><![CDATA[%s]]></ToUserName>
									  <FromUserName><![CDATA[%s]]></FromUserName>
									  <CreateTime>%s</CreateTime>
									  <MsgType><![CDATA[text]]></MsgType>
									  <Content><![CDATA[%s]]></Content>
								   </xml>";
						break;
						case 'CLICK':
							// 获取key值
							$EventKey = $object->EventKey;
							if($EventKey =="笑话"){
								$ch = curl_init();

								// 2. 设置抓取url地址
								$url = "http://www.kuitao8.com/api/joke";

								// 3. 抓取方式为get
								curl_setopt($ch,CURLOPT_URL,$url);
								// 返回页面为文件流
								curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

								// 执行
								$data = curl_exec($ch);

								// 关闭资源
								curl_close($ch);

								// 转换为数组
								$res = json_decode($data,true);
								// var_dump($res);die;
								$Content = $res['content'];

							$MsgReply = "<xml>
										  <ToUserName><![CDATA[%s]]></ToUserName>
										  <FromUserName><![CDATA[%s]]></FromUserName>
										  <CreateTime>%s</CreateTime>
										  <MsgType><![CDATA[text]]></MsgType>
										  <Content><![CDATA[%s]]></Content>
									  	 </xml>";

							} elseif($EventKey == "天气") {
								$cn=curl_init();
								
								$url="http://apis.juhe.cn/simpleWeather/query?city=北京&key=5272f07883418d8eac96ee4ad7b5f701";
								//2.设置传输选项值
								// CURLOPT_URL 抓取url地址
								curl_setopt($cn,CURLOPT_URL,$url);
								// CURLOPT_RETURNTRANSFER 把数据以文件流的方式返回
								curl_setopt($cn,CURLOPT_RETURNTRANSFER,1);

								//3.执行curl
								$res=curl_exec($cn);
								//4.关闭curl
								curl_close($cn); 
								// echo $res;
								//把json格式数据转换为php数组
								$arr=json_decode($res,true);
								
								$Content="今日天气实时情况:"."温度:".$arr['result']['realtime']['temperature'].","."空气质量:".$arr['result']['realtime']['humidity'].","."天气情况:".$arr['result']['realtime']['info'].","."风向:".$arr['result']['realtime']['direct'].","."风力:".$arr['result']['realtime']['power'].","."湿度:".$arr['result']['realtime']['aqi'];
								$MsgReply="<xml>
									  <ToUserName><![CDATA[%s]]></ToUserName>
									  <FromUserName><![CDATA[%s]]></FromUserName>
									  <CreateTime>%s</CreateTime>
									  <MsgType><![CDATA[text]]></MsgType>
									  <Content><![CDATA[%s]]></Content>
									</xml>";
							}elseif($EventKey == "头条"){
								//聚合数据API接口使用
									//1.初始化curl
									$cn=curl_init();
									$url="http://v.juhe.cn/toutiao/index?type=yule&key=8dd4091ee42a9c53939b2123b6f29efe";
									// $url="http://apis.juhe.cn/simpleWeather/query?city=北京&key=5272f07883418d8eac96ee4ad7b5f701";
									//2.设置传输选项值
									// CURLOPT_URL 抓取url地址
									curl_setopt($cn,CURLOPT_URL,$url);
									// CURLOPT_RETURNTRANSFER 把数据以文件流的方式返回
									curl_setopt($cn,CURLOPT_RETURNTRANSFER,1);

									//3.执行curl
									$res=curl_exec($cn);
									//4.关闭curl
									curl_close($cn); 
									// echo $res;
									//把json格式数据转换为php数组
									$arr=json_decode($res,true);
									
									$dataarr=array(
											array(
												"Title"=>$arr['result']['data'][0]['title'],
												"Description"=>$arr['result']['data'][0]['category'],
												"PicUrl"=>$arr['result']['data'][0]['thumbnail_pic_s'],
												"Url"=>$arr['result']['data'][0]['url']
											)
											);
									$str3="";
									//遍历
									foreach($dataarr as $key=>$value){
										$str3.="<item>
												<Title><![CDATA[".$value['Title']."]]></Title>
												<Description><![CDATA[".$value['Description']."]]></Description>
												<PicUrl><![CDATA[".$value['PicUrl']."]]></PicUrl>
												<Url><![CDATA[".$value['Url']."]]></Url>
												</item>";
									}
									//回复1对应的图文消息
									$MsgReply="<xml>
											<ToUserName><![CDATA[%s]]></ToUserName>
											<FromUserName><![CDATA[%s]]></FromUserName>
											<CreateTime>%s</CreateTime>
											<MsgType><![CDATA[news]]></MsgType>
											<ArticleCount>".count($dataarr)."</ArticleCount>
											<Articles>".$str3."</Articles>
											</xml>";
								}elseif ($EventKey == "资料") {
									$Content="TP5课件链接：https://pan.baidu.com/s/1xHfuM1Gez80w0HUH6OitZg 提取码：lo2l 
											TP5视频链接:链接：https://pan.baidu.com/s/1ERyWLh53q091YeP4HgB3aw 提取码：9ew5 
								高清无码哦";
											$MsgReply="<xml>
											  <ToUserName><![CDATA[%s]]></ToUserName>
											  <FromUserName><![CDATA[%s]]></FromUserName>
											  <CreateTime>%s</CreateTime>
											  <MsgType><![CDATA[text]]></MsgType>
											  <Content><![CDATA[%s]]></Content>
											</xml>";
								}
					break;
									
			}
			
	}

//通配符替换
$info = sprintf($MsgReply,$FromUserName,$ToUserName,time(),$Content);
echo $info;