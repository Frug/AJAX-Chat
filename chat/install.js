/*
 * @package AJAX_Chat
 * @author Mirko Girgenti (Bomdia)
 * @copyright (c) Sebastian Tschan
 * @license Modified MIT License
 * @link https://blueimp.net/ajax/
 */
function isFilled(){
    usr = document.login.username.value;
    pwd = document.login.passwd.value;
    host = document.login.host.value;
    if(usr.length==0){var p1=false;error('uer');}else{var p1=true;}
    if(host.length==0){var p3=false;error('her');}else{var p3=true;}
    if(p1 && p3){return true;}else{return false;}
}
function error(id){
    var cur = document.getElementById(id);
    cur.style.display="initial";
    cur.innerHTML="Error empty camp.";
}
function Login(){
    if(isFilled()){
        var xmlhttp;
        if (window.XMLHttpRequest){xmlhttp=new XMLHttpRequest();}else{xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");}
        var sending = "username="+document.login.username.value+"&passwd="+document.login.passwd.value+"&host="+document.login.host.value;
        xmlhttp.open("POST","sql.php?login",true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(sending);
        xmlhttp.onreadystatechange=function()
        {
          if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var st =document.getElementById("status");
                var text=xmlhttp.responseText;
                text=text.split("|");
                if(text[0]=="true"){
                    st.className="green overlay";
                    tocken=text[1];
                }else if(text[0]=="false"){
                    st.className="red overlay";
                }else{
                    st.innerHTML="errore : "+text;
                }
            }
        }
    }
}
function isFilled2(){
    mydb=document.setting.mydb.value;
    cbn=document.setting.cbn.value;
    dbt=document.setting.dbantime.value;
    if(mydb.length==0){var p1=false;error('dber');}else{var p1=true;}
    if(cbn.length==0){var p2=false;error('cber');}else{var p2=true;}
    if(dbt.length==0){var p3=false;error('dbter');}else{var p3=true;}
    if(p1 && p2 && p3){return true;}else{return false;}
}
function SSetting(){
    if(isFilled2()){
        var xmlhttp;
        if (window.XMLHttpRequest){xmlhttp=new XMLHttpRequest();}else{xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");}
        var sending = "tocken="+tocken+"&setting=host##"+host+"|user##"+usr+"|passwd##"+pwd+"|mydb##"+mydb+"|language##"+document.setting.language.value+"|style##"+document.setting.style.value+"|PChannel##"+document.setting.privchannel.value+"|PMessage##"+document.setting.privmsg.value+"|GLogin##"+document.setting.glogin.value+"|GWrite##"+document.setting.gwrite.value+"|GName##"+document.setting.gusr.value+"|Nchange##"+document.setting.usrchange.value+"|UmsgDelete##"+document.setting.usrdmsg.value+"|chatbot##"+cbn+"|DBanTime##"+dbt;
        xmlhttp.open("POST","sql.php?install",true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(sending);
        xmlhttp.onreadystatechange=function()
        {
          if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var st =document.getElementById("status");
                var text=xmlhttp.responseText;
                if(text=="true"){
                    st.className="green overlay";
                }else if(text=="false"){
                    st.className="red overlay";
                }else{
                    st.innerHTML="errore : "+text;
                }
            }
        }
    }
}
function injectGuiP2(){
    var xmlhttp;
    if (window.XMLHttpRequest){xmlhttp=new XMLHttpRequest();}else{xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");}
    var sending = "tocken="+tocken;
    xmlhttp.open("POST","sql.php?htmlguip2",true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(sending);
    xmlhttp.onreadystatechange=function()
        {
          if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var text=xmlhttp.responseText;
                document.body.innerHTML=text;
            }
        }
}
function Anim(id){
    window.setTimeout( function() {
        var div=document.getElementById(id);
        if(div.className=="red overlay"){
            div.className="red deoverlay";
        }else if(div.className=="green overlay"){
            var pdiv=div.parentNode.parentNode;
            var title=document.getElementById('title');
            var flog=document.getElementById('flogin');
            pdiv.style.background="transparent";
            title.style.opacity="0";
            flog.style.opacity="0";
            div.className="green deoverlay2";
            window.setTimeout( function() {
                pdiv.style.display="none";
                injectGuiP2();
            }, 500 );
        }
    }, 500 );
}
function Anim2(){
id="status";
    window.setTimeout( function() {
        var div=document.getElementById(id);
        if(div.className=="red overlay"){
            div.className="red deoverlay";
            div.innerHTML='<span id="cinstalled">Error control setting specially database name</span>';
        }else if(div.className=="green overlay"){
            div.style.height="100%"
            div.innerHTML='<span id="cinstalled">Correctly Installed.<br><a href="index.php">go to chat</a></span>';
        }
    }, 500 );
}