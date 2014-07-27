<?php
require("Tools.class.php");
$tool= new Tool();
$tocken=$tool->GetTocken('../lib/tocken.txt');
$file="../lib/config.php";
$FILE=file($file);
$s1='$config[\'styleAvailable\']';
$s2='$config[\'styleDefault\']';
function KeyConversion($arr){
	$diz=range('a','j');
	$huk=array_keys($arr['themelist']);
	for($i=0;;$i++){
		if($i==count($diz)){break;}
		for($e=0;;$e++){
			if($e==count($huk)){break;}
			$huk[$e]=str_replace($diz[$i],$i,$huk[$e]);
			if ( $e & 1 ) {// se dispari
				$hak=explode('_',$huk[$e]);
				$hak[1]="attr";
				$huk[$e]=$hak[0].'_'.$hak[1];
			}
		}
	}
	$arr['themelist']=array_combine( $huk , $arr['themelist'] );
	return $arr;
}
function Git($file){
	$basic="https://raw.githubusercontent.com/bomdia/Ajax-Chat-Style-Repo/master/";
	return $basic.$file;
}
function GetThemeGit($url){
	global $tool;
	$huhu=$tool->xml2array(Git($url));
	$theme=$huhu['theme']['file']['name'];
	$themeKey=array_keys($theme);
	$basedir="../css/";
	if(in_array("extra",$themeKey)){
		$edir=$theme['extra_attr']['dir'];
		if(!file_exists($basedir.$edir)){@mkdir($basedir.$edir);}
		if(is_array($theme['extra']['images'])){
			for($i=0;;$i++){
				if($i==count($theme['extra']['images'])/2){break;}
				if(!file_exists($basedir.$edir.'/'.$theme['extra']['images'][$i])){
					$file=file_get_contents(Git($theme['extra']['images'][$i.'_attr']['url']));
					$handle=fopen($basedir.$edir.'/'.$theme['extra']['images'][$i],'wb');
					fwrite($handle,$file);
					fclose($handle);
				}
			}
		}else{
			if(!file_exists($basedir.$edir.'/'.$theme['extra']['images'])){
				$file=file_get_contents(Git($theme['extra']['images_attr']['url']));
				$handle=fopen($basedir.$edir.'/'.$theme['extra']['images'],'wb');
				fwrite($handle,$file);
				fclose($handle);
			}
		}
	}
	if(in_array("css",$themeKey)){
		if(!file_exists($basedir.$theme['css'])){
			$file=file_get_contents(Git($theme['css_attr']['url']));
			$handle=fopen($basedir.$theme['css'],'wb');
			fwrite($handle,$file);
			fclose($handle);
		}
	}
}
function GetLine($FILE,$s1,$s2){
	for($i=0;;$i++){
		if($i==count($FILE)){break;}
		$FILE[$i]=str_replace(" ", "", $FILE[$i]);
		$FILE[$i]=str_replace("	", "", $FILE[$i]);
		$FILE[$i]=explode("=",$FILE[$i]);
		if($FILE[$i][0]==$s1 or $FILE[$i][0]==$s2){$arr[]=$i;}
	}
	return $arr;
}
function GetAviable($FILE,$line){
	$curr=explode("=", $FILE[$line[0]]);
    $car=$curr[1];
    $car=str_replace(" array(","",$car);
    $car=str_replace(");","",$car);
    $car=str_replace("'","",$car);
	$car=str_replace("\n","",$car);
    $arr=explode(",",$car);
	return $arr;
}
function WriteAviable($FILE,$line,$arr){
	global $file,$FILE;
	$curr=explode("=", $FILE[$line[0]]);
	for($i=0;;$i++){
		if($i==count($arr)){break;}
		$arr[$i]="'".$arr[$i]."'";
	}
	$parr="array(".implode(",",$arr).");";
	$FILE[$line[0]]=$curr[0]."= ".$parr."\n";
	if(!file_put_contents($file, $FILE)){return false;}else{return true;}
}
function WriteDefault($FILE,$line,$str){
	global $file;
	$curr=explode("=", $FILE[$line[1]]);
	$FILE[$line[1]]=$curr[0]."= '".$str."';\n";
	if(!file_put_contents($file, $FILE)){return false;}else{return true;}
}
function ThemeList($arr){
	for($i=0;;$i++){
		if($i==count($arr['themelist'])/2){break;}
		$themelist[$arr['themelist'][$i]]=$arr['themelist'][$i."_attr"]['xmlurl'];
	}
	return $themelist;
}
function ThemePreview($url){
	global $tool;
	$huhu=$tool->xml2array(Git($url));
	if(!is_array($huhu['theme']['preview'])){
		$preview=$huhu['theme']['preview'];
	}else{
		for($i=0;;$i++){
			if($i==count($huhu['theme']['preview'])){break;}
			$preview[$i]=$huhu['theme']['preview'][$i];
		}
	}
	return $preview;
}
function ThemeDescription($url){
	global $tool;
	$huhu=$tool->xml2array(Git($url));
	$desc=$huhu['theme']['description'];
	return $desc;
}
function htmllist($xml){
	global $tool;
	$hu=KeyConversion($tool->xml2array(Git($xml)));
	$list=ThemeList($hu);
	$klist=array_keys($list);
	$div="";
	for($i=0;;$i++){
		if($i==count($list)){break;}
		$div=$div.'<div class="preview"><div class="dddtext name">'.$klist[$i].'</div><div class="dddtext description">'.ThemeDescription($list[$klist[$i]]).'</div><div class="image">';
		$themeimg=ThemePreview($list[$klist[$i]]);
		if(is_array($themeimg)){
			for($e=0;;$e++){
				if($e==count($themeimg)){break;}
				$div=$div.'<img width=300px src="'.Git($themeimg[$e]).'">';
			}
		}else{
			$div=$div.'<img width=300px src="'.Git($themeimg).'">';
		}
		$div=$div.'</div><div class="button"><input type="button" onclick="Install(\''.$klist[$i].'\',false)" value="Install"><input type="button" onclick="Install(\''.$klist[$i].'\',true)" value="Install and set default"></div></div>';
	}
	return $div;
}
if(isset($_GET['isLogged'])){
	header("Content-Type:text/plain");
	if(isset($_POST['tipe'])){
		$tipe=$_POST['tipe'];
		switch($tipe){
			case "smf":
				if(!@include("../../SSI.php")){exit('No File Included');}
				if($context['user']['is_admin']){
					echo "true|$tocken";
				}else{
					echo "false|";
				}
				break;
			case "phpbb3":
				define('IN_PHPBB', true);
				$phpEx = substr(strrchr(__FILE__, '.'), 1);
				if(!@include('../../common.'.$phpEx)){exit('No File Included');}
				$user->session_begin();
				if($user->data['group_id']==5){//group id 5 is admin group
					echo "true|$tocken";
				}else{
					echo "false|";
				}
				break;
			case "punbb":
				define('FORUM_ROOT', '../../');
				if(!@include(FORUM_ROOT.'include/common.php')){exit('No File Included');}
				if($forum_user['group_id']==1){//group id 1 is admin group
					echo "true|$tocken";
				}else{
					echo "false|";
				}
				break;
			case "fluxbb":
				define('PUN_ROOT', '../../');
				if(!@include(PUN_ROOT.'include/common.php')){exit('No File Included');}
				if($pun_user['group_id']==1){//group id 1 is admin group
					echo "true|$tocken";
				}else{
					echo "false|";
				}
				break;
			case "mybb":
				define('IN_MYBB', 1);
				if(!@include('../../global.php')){exit('No File Included');}
				if($mybb->user['usergroup']==4){//group id 4 is admin group
					echo "true|$tocken";
				}else{
					echo "false|";
				}
				break;
			default:
				echo "false|";
		}
	}
	exit;
}
if(isset($_GET['login'])){
	header("Content-Type:text/plain");
	if(isset($_POST['username']) and isset($_POST['passwd']) and isset($_POST['host'])){
		$usr=$_POST['username'];
		$pwd=$_POST['passwd'];
		$host=$_POST['host'];
		$link = @mysqli_connect($host,$usr,$pwd);
		if(!$link){
			echo "false|";
		}else{
			mysqli_close($link);
			echo "true|$tocken";
		}
	}
	exit;
}
if(isset($_GET['guilogin'])){
	echo '
<div id="main">
            <div id="title">
            MySql Login
            </div>
            <div id="login">
                <form id="flogin" name="login">
                 <table>
                    <tr>
                        <td>Username </td>
                        <td><input type="text" name="username"></td>
                        <td><div id="uer" class="err" style="display: none;"></div></td>
                    </tr>
                    <tr>
                        <td>Password </td>
                        <td><input type="password" name="passwd"></td>
                    </tr>
                    <tr>
                        <td>Hostname </td>
                        <td><input type="text" value="localhost" name="host"></td>
                        <td><div id="her" class="err" style="display: none;"></div></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="button" value="Login" onclick="Login();Anim(\'status\')"></td>
                    </tr>
                </table>
                </form>
                <div id="status" class="red"></div>
            </div>
        </div>
	';
	exit;
}
if(isset($_GET['htmlList'])){
	echo htmllist('main.xml');
	exit;
}
if(isset($_GET['guiinstall'])){
	if(isset($_POST['tocken'])){
		if($_POST['tocken']==$tocken){
			echo '
<div id="main">
	<div id="title">
		<span id="textt" class="bolder white dddtext">Aviable Theme</span>
		<div style="float:right"><a href="javascript:void(0)" onclick="LoadList()"><img width="50px" src=img/refresh.png></a>
	</div>
	<div id="content"><div id="scroll"></div></div>
	<div style="display:none" id="status" class="popup red"></div>
			';
		}else{echo "the gived tocken is invalid";}
	}
	exit;
}
if(isset($_GET['install'])){
	if(isset($_POST['tocken']) and isset($_POST['name']) and isset($_POST['default'])){
		if($_POST['tocken']==$tocken){
			$hu=KeyConversion($tool->xml2array(Git('main.xml')));
			$list=ThemeList($hu);
			$ccss=GetAviable($FILE,GetLine($FILE,$s1,$s2));
			$name=$_POST['name'];
			if(!in_array(str_replace(" ","-",$name),$ccss)){GetThemeGit($list[$name]);$ccss[count($ccss)]=str_replace(" ","-",$name);}
			if(WriteAviable($FILE,GetLine($FILE,$s1,$s2),$ccss)){$is="true";}else{$is="false";}
			if($_POST['default']=="true" and $is=="true"){
				if(WriteDefault($FILE,GetLine($FILE,$s1,$s2),str_replace(" ","-",$name))){$is="true";}else{$is="false";}
			}
			echo $is;
		}else{echo "the gived tocken is invalid";}
	}else{echo "not all post variable is set";}
	exit;
}
?>
<html>
	<head>
		<title>Style Installer</title>
        <link rel="stylesheet" type="text/css" href="index.css">
        <script src="index.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
    </head>
	<body>
		<div id="main">
			<div id="title">
				<span id="textt" class="bolder white dddtext">Style installer for ajax chat from bomdia</span>
			</div>
			<div id="content">
				<div id="scroll">
					<div class="r1">
						<img width="170px" onclick="isLogged('standalone')" src="img/standalone.png">
						<img width="170px" onclick="isLogged('smf')" src="img/smf.png">
						<img width="170px" onclick="isLogged('phpbb3')" src="img/phpbb3.png">
					</div>
					<div class="r2">
						<img width="170px" onclick="isLogged('punbb')" src="img/punbb.png">
						<img width="170px" onclick="isLogged('fluxbb')" src="img/fluxbb.png">
						<img width="170px" onclick="isLogged('mybb')" src="img/mybb.png">
					</div>
				</div>
			</div>
		</div>
	</body>
</html>