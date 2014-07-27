if(!Object.keys){Object.keys=function (o){var ks=[];for(var k in o) ks.push(k);return ks;};}
if (window.XMLHttpRequest){xmlhttp=new XMLHttpRequest();}else{xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");}

function Install(name,def){
	var sending = "tocken="+tocken+"&name="+name+"&default="+def;
	xmlhttp.open("POST","index.php?install",true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send(sending);
	var stat=document.getElementById("status");
	xmlhttp.onreadystatechange=function()
	{
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			var text=xmlhttp.responseText;
			stat.style.display="initial";
			if(text=="true"){
				stat.className="popup green";
				stat.innerHTML='<img  width="300" src="img/v.png">';
				window.setTimeout( function() {stat.style.display="none";}, 3*1000);
			}else if(text[0]=="false"){
				stat.className="popup red";
				stat.innerHTML='<img  width="300" src="img/x.png">';
				window.setTimeout( function() {stat.style.display="none";}, 3*1000);
			}else{
				stat.className="popup red";
				stat.innerHTML="error: "+text;
			}
		}
	}
}
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
function LoadLogin(){
	xmlhttp.open("GET","index.php?guilogin",true);
	xmlhttp.send();
	xmlhttp.onreadystatechange=function()
	{
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			var text=xmlhttp.responseText;
			var style=document.getElementsByTagName('link')[0];
			style.href="login.css";
			document.body.innerHTML=text;
		}
	}
}
function LoadList(){
	xmlhttp.open("GET","index.php?htmlList",true);
	xmlhttp.send();
	xmlhttp.onreadystatechange=function()
	{
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			var text=xmlhttp.responseText;
			document.getElementById('scroll').innerHTML=text;
		}
	}
}
function LoadInstall(){
	var style=document.getElementsByTagName('link')[0];
	var sending = "tocken="+tocken;
	xmlhttp.open("POST","index.php?guiinstall",true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send(sending);
	xmlhttp.onreadystatechange=function()
	{
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			var text=xmlhttp.responseText;
			if(style.href!="index.css"){style.href="index.css";}
			document.body.innerHTML=text;
			LoadList();
		}
	}
}
function Login(){
    if(isFilled()){
        var xmlhttp;
        if (window.XMLHttpRequest){xmlhttp=new XMLHttpRequest();}else{xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");}
        var sending = "username="+document.login.username.value+"&passwd="+document.login.passwd.value+"&host="+document.login.host.value;
        xmlhttp.open("POST","index.php?login",true);
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
function isLogged(tipe){
	var sending = "tipe="+tipe;
	xmlhttp.open("POST","index.php?isLogged",true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send(sending);
	xmlhttp.onreadystatechange=function()
	{
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			var text=xmlhttp.responseText;
			text=text.split("|");
			if(text[0]=="true"){
				tocken=text[1];
				LoadInstall();
			}else if(text[0]=="false"){
				if(tipe=="standalone"){
					LoadLogin();
				}
			}else{
				
			}
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
                LoadInstall(tocken);
            }, 500 );
        }
    }, 500 );
}