<?php
set_time_limit(0);
require_once('color.php');

function friendlist($token){
	$a = json_decode(file_get_contents('https://graph.facebook.com/me/friends?access_token='.$token), true);
	return $a['data'];
}
function last_active($id, $tok){
	$a = json_decode(file_get_contents('https://graph.facebook.com/'.$id.'/feed?access_token='.$tok.'&limit=1'), true);
	$date = $a['data'][0]['created_time'];
	$aa = strtotime($date);
	return date('Y', $aa);
}

function unfriend($id, $token){
	$url = 'https://graph.facebook.com/me/friends?uid='.$id.'&access_token='.$token;
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
	if($result == true){
		$unf = Console::green('[Temen/mantan bangsad Terhapos !]');
	} else {
		$unf = Console::red('[Gagal Move On Sama Mantan]');
	}
	return $unf;
}

echo Console::blue("     Hapos Pertemanan Sama Mantan !\n");
echo Console::blue("        Tidak Aktif/Mati !\n\n");

//INPUT
echo "Masukin Token FB Bitch ! : ";
$fbtoken = trim(fgets(STDIN));
echo "Masukin Tahun Yang Mau Di Hapus ? : ";
$year = trim(fgets(STDIN));
echo "\n";

$FL = friendlist($fbtoken);
foreach($FL as $list){
	$name = $list['name'];
	$id = $list['id'];
	$date = last_active($id, $fbtoken);
	if($date < $year){
		echo Console::red('[Mantan Telah Mati]').' '.$name.' ~ '.$date.' '.unfriend($id, $fbtoken);
		echo "\r\n";
	}else{
		echo Console::green('[Mantan Masik Hidup]').' '.$name.' ~ '.$date;
		echo "\r\n";
	}
}
