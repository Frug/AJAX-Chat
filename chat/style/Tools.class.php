<?php
    /**
     * Bomdia Generic Tools
     * @author Bomdia <bomdia.the.troll@gmail.com> https://github.com/bomdia
     * @license Free to use but dont remove the author, license and copyright
     * @copyright © 2014 Bomdia
     */
class Tool{
    public function Dictionary(){
        $dizp1=range('a','z');
        $dizp2=range('A','Z');
        $dizp3=range(0,9);
        $diz=array_merge($dizp1,$dizp2,$dizp3);
        return $diz;
    }
    public function Salter($diz="",$length=5){
        if($diz==""){$diz=$this->Dictionary();}
		$tocken=null;
		for($i=0;;$i++){
			if($i==$length){break;}
			$tocken=$tocken.$diz[rand(0,61)];
		}
		return $tocken;
    }
    public function sha1bom($world,$dizionario="",$doublesha=false,$salt=''){
        if($dizionario==""){$dizionario=$this->Dictionary();}
        if($salt==''){$salt=$this->Salter($dizionario);}
        $shaw=$salt.$world.$salt;
        if($doublesha){
            return array('shaw' => sha1(sha1($shaw)),'salt' => $salt,'type'=>'doublesha');
        }else{
            return array('shaw' => sha1($shaw),'salt' => $salt,'type'=>'normalsha');
        }
    }
    public function Exploder($str,$divider1="&",$divider2="="){
        $a = explode($divider1, $str);$na=count($a);
        for ($i=0; ;$i++) {
            if($i==$na){break;}
            $b = explode($divider2, $a[$i]);
            $cd[$b[0]]=$b[1];
        }
        return $cd;
    }
    public function Imploder($array,$divider="&"){
        if(!is_array($array)){break;}
        $hu=implode($divider,array_keys($array));
        $v1=explode($divider,$hu);
        $na=count($v1);
        $srt="";
        for ($i=0; ;$i++) {
            if($i==$na){break;}
        $str[$i]=$array[$v1[$i]];
        }
        for ($i=0; ;$i++) {
            if($i==$na){break;}
        if($srt==""){$srt=$srt.$v1[$i]."=".$str[$i];}else{$srt=$srt.$divider.$v1[$i]."=".$str[$i];}
        }
        return $srt;
    }
	public function CliGet($argvs){
	global $_GET;
	$charg=count($argvs);
		if($charg>1){
			for($i=1;;$i++){
				if($i==$charg){break;}
				$get[]=$argvs[$i];
			}
			$na=count($get);
			for ($i=0; ;$i++) {
				if($i==$na){break;}
					$b = explode('=', $get[$i]);
					if(isset($b[1])){
					$_GET[$b[0]]=$b[1];
				}else{
					$_GET[$b[0]]='';	
				}
			}
		}
	}
    public function Write($w,$h,$rgb,$aviablefont,$font,$text,$image="false",$fontY,$fontX,$fontS,$fontA,$idst){
        /*
        $w width
        $h height
        $rgb r=255|g=255|b=255 color in rgb
        $aviablefont an array that contain the aviable font and his location like this $font['Normal'] = array("Normals"=>"core/font/segoeui.ttf");
        $font to use a str to pass to Exploder tools style=Normal|type=Normals
        $text word to write
        $image an additional image to use as background Default false if not the Url absolute or not "http://ex.com/image.png" "core/image.png" "/core/image.png"
        $fontY coordinate in y axis to positionate the text 
        $fontX coordinate in x axis to positionate the text
        $fontS size of text
        $fontA from 0 to 127 alpha of text
        $idst se null restituisce il codice del png se no scrive un png nel percorso indicato
        */
        $RGB=$this->Exploder($rgb);
        $FONT=$this->Exploder($font);
        $fn=$aviablefont[$FONT['style']][$FONT['type']];
        $r=$RGB["r"];$g=$RGB["g"];$b=$RGB["b"];

        $img = imagecreatetruecolor($w,$h);
        imagealphablending($img, true); 
        $bs = imagecolorallocatealpha($img,0,0,0,127);
        imagefill($img, 0, 0,$bs);
        imagesavealpha($img, TRUE);
        if($image!="false"){
        $s = getimagesize($image);
        $imag=imagecreatefrompng($image);
        imagecopyresampled($img,$imag,0,0,0,0,$w,$h,$s[0],$s[1]);
        }
        $color = imagecolorallocate($img,$r,$g,$b);
        imagettftext($img,$fontS,$fontA,$fontX,$fontY,$color,$fn,$text);
        imagepng($img);
        imagedestroy($img,$idst);
    }
    public function xml2array($url, $get_attributes = 1, $priority = 'tag'){
        $contents = "";
        if (!function_exists('xml_parser_create'))
        {
            return array ();
        }
        $parser = xml_parser_create('');
        if (!($fp = @ fopen($url, 'rb')))
        {
            return array ();
        }
        while (!feof($fp))
        {
            $contents .= fread($fp, 8192);
        }
        fclose($fp);
        xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8");
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, trim($contents), $xml_values);
        xml_parser_free($parser);
        if (!$xml_values)
        return; //Hmm...
        $xml_array = array ();
        $parents = array ();
        $opened_tags = array ();
        $arr = array ();
        $current = & $xml_array;
        $repeated_tag_index = array (); 
        foreach ($xml_values as $data)
        {
            unset ($attributes, $value);
            extract($data);
            $result = array ();
            $attributes_data = array ();
            if (isset ($value))
            {
                if ($priority == 'tag')
                $result = $value;
                else
                $result['value'] = $value;
            }
            if (isset ($attributes) and $get_attributes)
            {
                foreach ($attributes as $attr => $val)
                {
                    if ($priority == 'tag')
                    $attributes_data[$attr] = $val;
                    else
                    $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
                }
            }
            if ($type == "open")
            { 
                $parent[$level -1] = & $current;
                if (!is_array($current) or (!in_array($tag, array_keys($current))))
                {
                    $current[$tag] = $result;
                    if ($attributes_data)
                    $current[$tag . '_attr'] = $attributes_data;
                    $repeated_tag_index[$tag . '_' . $level] = 1;
                    $current = & $current[$tag];
                }
                else
                {
                    if (isset ($current[$tag][0]))
                    {
                        $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                        $repeated_tag_index[$tag . '_' . $level]++;
                    }
                    else
                    { 
                        $current[$tag] = array (
                        $current[$tag],
                        $result
                        ); 
                        $repeated_tag_index[$tag . '_' . $level] = 2;
                        if (isset ($current[$tag . '_attr']))
                        {
                            $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                            unset ($current[$tag . '_attr']);
                        }
                    }
                    $last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
                    $current = & $current[$tag][$last_item_index];
                }
            }
            elseif ($type == "complete")
            {
                if (!isset ($current[$tag]))
                {
                    $current[$tag] = $result;
                    $repeated_tag_index[$tag . '_' . $level] = 1;
                    if ($priority == 'tag' and $attributes_data)
                    $current[$tag . '_attr'] = $attributes_data;
                }
                else
                {
                    if (isset ($current[$tag][0]) and is_array($current[$tag]))
                    {
                        $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                        if ($priority == 'tag' and $get_attributes and $attributes_data)
                        {
                            $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                        }
                        $repeated_tag_index[$tag . '_' . $level]++;
                    }
                    else
                    {
                        $current[$tag] = array (
                        $current[$tag],
                        $result
                        ); 
                        $repeated_tag_index[$tag . '_' . $level] = 1;
                        if ($priority == 'tag' and $get_attributes)
                        {
                            if (isset ($current[$tag . '_attr']))
                            { 
                                $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                                unset ($current[$tag . '_attr']);
                            }
                            if ($attributes_data)
                            {
                                $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                            }
                        }
                        $repeated_tag_index[$tag . '_' . $level]++; //0 and 1 index is already taken
                    }
                }
            }
            elseif ($type == 'close')
            {
                $current = & $parent[$level -1];
            }
        }
        return ($xml_array);
    }
	public function lastmiui($urlxml="http://www.miui.it/tag/traduzione/feed/"){
        $arrayxml=$this->xml2array($urlxml);
        $last=$arrayxml['rss']['channel']['item'][0]['title'];
        $exp=array_merge(range('a','z'),range('A','Z'),array(' '));
        $delasted=str_replace($exp, "", $last);
        return $delasted;
    }
    public function Sec($str){echo("[".$str."]\n");}
    public function Vrb($str1,$str2){echo("$str1=$str2\n");}
    public function mrequire($array){
        if(!is_array($array)){require_once($array);}else{
        $lenght=count($array);
        for($i=0;;$i++){
        if($lenght==$i){break;}
        require_once($array[$i]);
        }
        }
    }
    private function StoreTocken($tocken,$file){
		if(!file_put_contents($file, $tocken)){return false;}else{return true;}
	}
	public function GetTocken($file){
		if(file_exists($file)){
			$difftime=time()-filemtime($file);
			if($difftime<1800){
				$tocken = file($file)[0];
			}else{
				$tocken = $this->Salter("",100);
				$this->StoreTocken($tocken,$file);
			}
		}else{
			$tocken = $this->Salter("",100);
			$this->StoreTocken($tocken,$file);
		}
		return $tocken;
	}

	}
?>
