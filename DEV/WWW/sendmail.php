<?
////////////////////////////////////////////////////////////////////////////////
define("DOMAIN",	"78.108.80.11");
define("MYSQLUSER",	"jivayasredaorg");
define("MYSQLPASS",	"99sqeghfdkty");
define("MYSQLDB",	"jivayasredaorg_rep");
////////////////////////////////////////////////////////////////////////////////
ob_start();
db_connect();
mysql_set_charset('utf8');
ob_flush();

$type=$_POST['type'];

if ($type==1){
	$name=$_POST['nm'];
	$phone=$_POST['cntc'];
	$email_title='Zakaz obratnogo zvonka';
	$email_text='Имя: '.$name.'<br> Телефон: '.$phone.'<br> Когда: '.date("H:i d.m.y");
}
elseif ($type==2){
	$name=$_POST['nm'];
	$mail=$_POST['ml'];
	$phone=$_POST['pn'];
	$site=$_POST['st'];
	$email_title='Zayavka s sayta';
	$email_text='Имя: '.$name.'<br> Телефон: '.$phone.'<br> Когда: '.date("H:i d.m.y");
}


$title=convert_charset($email_title);
$message=convert_charset($email_text);
$adress='agrabarnick@gmail.com';
//
$result=order($name,$phone,$site);
// sms('79263713789',$name,$phone);
// sms('79030920025',$name,$phone);
//

$headers  = 'MIME-Version: 1.0
Content-type: text/html; charset=koi8-r
From: Jivayasreda.org <mailer@jivayasreda.org>
';

$ok=mail($adress,$title,$message,$headers);	
print $ok;
//////////////////////////////////////////////////////////////////////////////
//functions
function convert_charset($item) {
	if ($unserialize = unserialize($item)) {
		foreach ($unserialize as $key => $value) {
			$unserialize[$key] = @iconv('utf-8', 'koi8-r', $value);
		}
		$serialize = serialize($unserialize);
        return $serialize;
	}
	else {
		return @iconv('utf-8', 'koi8-r', $item);
	}
}
function db_connect()	{
	global $db;
	if ($db)
		return;
	$db = mysql_connect(DOMAIN, MYSQLUSER, MYSQLPASS) or die('Could not connect to mysql server.' );
	mysql_select_db(MYSQLDB, $db);
	if (!$db)	{
		die(mysql_error());
		echo "Couldn't open database!\n";
		exit;
	}
}
function order($name,$phone,$site)	{
	//$name=$post['name'];
	//$phone=$post['phone'];
	//$purpose=$post['purpose'];
	//
	$sql="INSERT INTO direct_order 
				(`name`,`phone`,`purpose`,`date`)
				VALUES 
				('$name','$phone','$site',NOW())";

	$result = mysql_query($sql) or die(mysql_error());
	$id_order=mysql_insert_id();

return 1;
}
/////////////////////////////////////////////////////////////////////////////////
function sms($phone0,$name,$phone) {
		$text='jivaya, new order - '.$name.' '.$phone;
		//echo 'text - '.$text.'<br>';
		$text=iconv("utf-8", "windows-1251//IGNORE", $text);	
		$url='http://smsc.ru/sys/send.php';
		$data['login']='cybaup';
        $data['psw']='uphtdjk.wbz';
        $data['phones']=$phone0;
        $data['mes']=$text;
		//
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_POST, 10);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$result = curl_exec($ch);
		curl_close($ch);
return 1;
}
?>