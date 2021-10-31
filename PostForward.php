/**#
  
*/
<?php 
    $requestUrl=isset($_POST["url"])?$_POST["url"]:exit("参数url丢失");
    $requestBody=isset($_POST["body"])?$_POST["body"]:false;
    $requestMethod=isset($_POST["method"])?$_POST["method"]:"post"; 
    $ch =curl_init(); 
    curl_setopt($ch,CURLOPT_URL,$requestUrl); 
    //构造随机ip
    $ip_long = array(
        array('607649792', '608174079'), //36.56.0.0-36.63.255.255
        array('1038614528', '1039007743'), //61.232.0.0-61.237.255.255
        array('1783627776', '1784676351'), //106.80.0.0-106.95.255.255
        array('2035023872', '2035154943'), //121.76.0.0-121.77.255.255
        array('2078801920', '2079064063'), //123.232.0.0-123.235.255.255
        array('-1950089216', '-1948778497'), //139.196.0.0-139.215.255.255
        array('-1425539072', '-1425014785'), //171.8.0.0-171.15.255.255
        array('-1236271104', '-1235419137'), //182.80.0.0-182.92.255.255
        array('-770113536', '-768606209'), //210.25.0.0-210.47.255.255
        array('-569376768', '-564133889'), //222.16.0.0-222.95.255.255
    );
    $rand_key = mt_rand(0, 9);
    $ip= long2ip(mt_rand($ip_long[$rand_key][0], $ip_long[$rand_key][1]));

    $header = array("Connection: Keep-Alive","Accept: text/html, application/xhtml+xml, */*", "Pragma: no-cache", "Accept-Language: zh-Hans-CN,zh-Hans;q=0.8,en-US;q=0.5,en;q=0.3","User-Agent: Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; WOW64; Trident/6.0)",'CLIENT-IP:'.$ip,'X-FORWARDED-FOR:'.$ip);
  
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_HEADER,false);   
    curl_setopt($ch,CURLOPT_HTTPHEADER,$header);     
	/**#跳过HTTPS检查*/
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
    if($requestMethod=="post"){
        curl_setopt($ch, CURLOPT_POST, 1); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $requestBody); 
      }
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,10);
    curl_setopt($ch,CURLOPT_TIMEOUT,10);   
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    $content = curl_exec($ch);  
    $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE); 
    curl_close($ch); 
    if (curl_error($ch)) { 
        $responseContentType="content-type:text/json";
        $content=json_encode(array("code"=>401,"message"=>curl_error($ch)));
    } else {
        $resInfo=curl_getinfo($ch);
        $responseContentType= $resInfo["content_type"] ; 
    }
    
    ob_clean();
    ob_end_clean();
    header("Content-type: ".$responseContentType); 
    echo  $content;
    exit();