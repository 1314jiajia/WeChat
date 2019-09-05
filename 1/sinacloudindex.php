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

			echo "error";
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

			case 'text':

			// 获取客户端发送的消息(当做回复消息)
			$Content = $object->Content;

			// 微信公众号把处理过的消息直接返回给客户端
			$MsgReply="<xml>
						  <ToUserName><![CDATA[%s]]></ToUserName>
						  <FromUserName><![CDATA[%s]]></FromUserName>
						  <CreateTime>%s</CreateTime>
						  <MsgType><![CDATA[text]]></MsgType>
						  <Content><![CDATA[%s]]></Content>
					   </xml>";
					break;
			
		//消息类型 图片类型  图片消息交互
		case 'image':
				
				// 获取客户端发送的消息(当做回复消息)
				$Content=$object->MediaId;
				
				// 微信公众号把处理过的消息直接返回给客户端
				$MsgReply="<xml>
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
				$Content=$object->MediaId;
			
				// 微信公众号把处理过的消息直接返回给客户端
			
					$MsgReply="<xml>
							  <ToUserName><![CDATA[%s]]></ToUserName>
							  <FromUserName><![CDATA[%s]]></FromUserName>
							  <CreateTime>%s</CreateTime>
							  <MsgType><![CDATA[voice]]></MsgType>
							  <Voice>
							    <MediaId><![CDATA[%s]]></MediaId>
							  </Voice>
							</xml>";
			break;

			case "video":

				// 获取客户端发送的消息(当做回复消息)
				$Content=$object->video;
			
				// 微信公众号把处理过的消息直接返回给客户端
			
					$MsgReply="<xml>
							  <ToUserName><![CDATA[%s]]></ToUserName>
							  <FromUserName><![CDATA[%s]]></FromUserName>
							  <CreateTime>%s</CreateTime>
							  <MsgType><![CDATA[video]]></MsgType>
							  <Voice>
							    <MediaId><![CDATA[%s]]></MediaId>
							  </Voice>
							</xml>";

			break;
	}

//通配符替换
$info = sprintf($MsgReply,$FromUserName,$ToUserName,time(),$Content);
echo $info;