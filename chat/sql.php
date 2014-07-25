<?php
/*
 * @package AJAX_Chat
 * @author Mirko Girgenti (Bomdia)
 * @copyright (c) Sebastian Tschan
 * @license Modified MIT License
 * @link https://blueimp.net/ajax/
 */
$ChatPath=dirname($_SERVER['SCRIPT_FILENAME']).'/';
$FileBase=$ChatPath."lib/config.php";
$file = file($FileBase);
function tocken(){
    $dizp1=range('a','z');
    $dizp2=range('A','Z');
    $dizp3=range(0,9);
    $diz=array_merge($dizp1,$dizp2,$dizp3);
    $tocken=null;
    for($i=0;;$i++){
        if($i==100){break;}
        $tocken=$tocken.$diz[rand(0,61)];
    }
    return $tocken;
}
function StoreTocken($tocken){
    $file='lib/tocken.txt';
    $handle=@fopen($file,"wb");
    @fwrite($handle,$tocken);
    @fclose($handle);
}
function GetTocken(){
    $file='lib/tocken.txt';
    if(file_exists($file)){
        $difftime=time()-filemtime($file);
        if($difftime<1800){
            $tocken = file($file)[0];
        }else{
            $tocken = tocken();
            StoreTocken($tocken);
        }
    }else{
        $tocken = tocken();
        StoreTocken($tocken);
    }
    return $tocken;
}
function Exploder($str,$divider1="&",$divider2="="){
        $a = explode($divider1, $str);$na=count($a);
        for ($i=0; ;$i++) {
            if($i==$na){break;}
            $b = explode($divider2, $a[$i]);
            $cd[$b[0]]=$b[1];
        }
        return $cd;
    }
function replaceSetting($file,$line,$setting,$FileBase){
    $arrayp=array_keys($line);
    for($i=0;;$i++){if($i==count($line)){break;}
        $curr=explode("=", $file[$line[$arrayp[$i]]-1]);
        $csetting=$setting[$arrayp[$i]];
        if($csetting=="true" or $csetting=="false"){
            $curr[1]=" ".$csetting.";\n";
        }else{
            $curr[1]=" '".$csetting."';\n";
        }
        $curr=implode("=",$curr);
        $file[$line[$arrayp[$i]]-1]=$curr;
    }
    $handle=@fopen($FileBase,"wb");
    if(!$handle){$p1=false;}else{$p1=true;}
    if(!@fwrite($handle,implode("",$file))){$p2=false;}else{$p2=true;}
    @fclose($handle);
    if($p1 and $p2){return true;}else{return false;}
}
function styleAviable($file){
    $curr=explode("=", $file[53]);
    $car=$curr[1];
    $car=str_replace("array(","",$car);
    $car=str_replace(");","",$car);
    $car=str_replace("'","",$car);
    $arr=explode(",",$car);
    $optstyle="<select name=\"style\">";
    for($i=0;;$i++){if($i==count($arr)){break;}
        if($arr[$i]=="prosilver"){
            $optstyle=$optstyle.'<option selected="selected">'.$arr[$i]."</option>";
        }else{
            $optstyle=$optstyle."<option>".$arr[$i]."</option>";
        }
    }
    $optstyle=$optstyle."</select>";
    return $optstyle;
}
function languageAviable($file){
    $curr=explode("=", $file[50], 2);
    $car=$curr[1];
    $car=str_replace(" array(","",$car);
    $car=str_replace(");","",$car);
    $car=str_replace("'","",$car);
    $a = explode(", ", $car);$na=count($a);
    for ($i=0; ;$i++) {
        if($i==$na){break;}
        $b = explode('=>', $a[$i]);
        $cd[$b[0]]=$b[1];
    }
    $arrayp=array_keys($cd);
    $lan="<select name=\"language\">";
    for($i=0;;$i++){if($i==count($arrayp)){break;}
        if($arrayp[$i]=="en"){
            $lan=$lan.'<option value="'.$arrayp[$i].'" selected="selected">'.$cd[$arrayp[$i]]."</option>";
        }else{
            $lan=$lan.'<option value="'.$arrayp[$i].'">'.$cd[$arrayp[$i]]."</option>";
        }
    }
    $lan=$lan."</select>";
    return $lan;
}
if(isset($_GET['login'])){
    header("Content-Type:text/plain");
    if(isset($_POST['username']) and isset($_POST['passwd']) and isset($_POST['host'])){
        $usr=$_POST['username'];
        $pwd=$_POST['passwd'];
        $host=$_POST['host'];
        @$link = mysqli_connect($host,$usr,$pwd);
        if(!$link){
            echo "false";
            echo "|";
        }else{
            mysqli_close($link);
            $tocken=GetTocken();
            echo "true";
            echo "|";
            echo $tocken;
        }
    }
}
if(isset($_GET['install'])){
    header("Content-Type:text/plain");
    if(isset($_POST['tocken']) and isset($_POST['setting'])){
        if($_POST['tocken']==GetTocken()){
            $setting=Exploder($_POST['setting'],"|","##");
            $link = mysqli_connect($setting['host'],$setting['user'],$setting['passwd'],$setting['mydb']);
            $query = file_get_contents("chat.sql");
            if(mysqli_multi_query($link, $query)){$p1=true;}else{$p1=false;}
            $line=array("host"=>25,"user"=>27,"passwd"=>29,"mydb"=>31,"language"=>49,"style"=>56,"PChannel"=>91,"PMessage"=>93,"GLogin"=>118,"GWrite"=>120,"GName"=>122,"Nchange"=>131,"UmsgDelete"=>138,"chatbot"=>143,"DBanTime"=>169);
            if(replaceSetting($file,$line,$setting,$FileBase)){$p2=true;}else{$p2=false;}
            if($p1 and $p2){echo "true";}else{echo "false";}
            @unlink('lib/tocken.txt');
        }
    }
}
if(isset($_GET['htmlguip2'])){
    if(isset($_POST['tocken'])){
        if($_POST['tocken']==GetTocken()){
            echo'
<div id="main2">
    <div id="title">
    Setting For Chat
    </div>
    <div id="setting">
        <form name="setting">
            <table>
                <tr>
                    <td>Database </td>
                    <td><input type="text" value="chat" name="mydb"></td>
                    <td><div id="dber" class="err" style="display: none;"></div></td>
                </tr>
                <tr>
                    <td>Language </td>
                    <td>'.languageAviable($file).'</td>
                </tr>
                <tr>
                    <td>Style </td>
                    <td>'.styleAviable($file).'</td>
                </tr>
                <tr>
                    <td>Private channel </td>
                    <td>yes<input type="radio" name="privchannel" checked value="true"/>no<input type="radio" name="privchannel" value="false"/></td>
                </tr>
                <tr>
                    <td>Private messages </td>
                    <td>yes<input type="radio" name="privmsg" checked value="true"/>no<input type="radio" name="privmsg" value="false"/></td>
                </tr>
                <tr>
                    <td>Username changeable </td>
                    <td>yes<input type="radio" name="usrchange" checked value="true"/>no<input type="radio" name="usrchange" value="false"/></td>
                </tr>
                <tr>
                    <td>Guest login </td>
                    <td>yes<input type="radio" name="glogin" checked value="true"/>no<input type="radio" name="glogin" value="false"/></td>
                </tr>
                <tr>
                    <td>Guest write </td>
                    <td>yes<input type="radio" name="gwrite" checked value="true"/>no<input type="radio" name="gwrite" value="false"/></td>
                </tr>
                <tr>
                    <td>Guest username changeable </td>
                    <td>yes<input type="radio" name="gusr" checked value="true"/>no<input type="radio" name="gusr" value="false"/></td>
                </tr>
                <tr>
                    <td>User Message Delete </td>
                    <td>yes<input type="radio" name="usrdmsg" checked value="true"/>no<input type="radio" name="usrdmsg" value="false"/></td>
                </tr>
                <tr>
                    <td>Chatbot name </td>
                    <td><input type="text" value="ChatBot" name="cbn"></td>
                    <td><div id="cber" class="err" style="display: none;"></div></td>
                </tr>
                <tr>
                    <td>Ban default time </td>
                    <td><input type="text" value="5" name="dbantime"></td>
                    <td><div id="dbter" class="err" style="display: none;"></div></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="button" value="Installa" onclick="SSetting();Anim2();"></td>
                </tr>
            </table>
        </form>
        <div id="status" class="red"></div>
    </div>
</div>
            ';
        }
    }
}
?>