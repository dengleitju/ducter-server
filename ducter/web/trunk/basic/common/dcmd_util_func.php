<?php
function xml_to_array( $xml )
{//array(1) { ["env"]=> array(2) { ["name"]=> string(2) "gu" ["aa"]=> string(4) "miao" } } 
   $reg = "/<(\\w+)[^>]*?>([\\x00-\\xFF]*?)<\\/\\1>/";
   if(preg_match_all($reg, $xml, $matches))
   {
      $count = count($matches[0]);
      $arr = array();
      for($i = 0; $i < $count; $i++)
      {
         $key = $matches[1][$i];
         $val = xml_to_array( $matches[2][$i] );  // 递归
         if(array_key_exists($key, $arr))
         {
            if(is_array($arr[$key]))
            {
              if(!array_key_exists(0,$arr[$key]))
              {
                $arr[$key] = array($arr[$key]);
              }
            }else{
              $arr[$key] = array($arr[$key]);
            }
            $arr[$key][] = $val;
         }else{
           $arr[$key] = $val;
         }
      }
      return $arr;
   }else{
      return $xml;
   }
}



function xmltoarray( $xml )
{
        if($xml){
                $arr = xml_to_array($xml);
                $key = array_keys($arr);
                return $arr[$key[0]];
        }else{
                return '';
        }

}

function html_edit($content) {
  $old = array("'", '"', "<", ">");
  $new = array("&#39;", "&quot;", "&lt;", "&gt;");
  return str_replace($old, $new, $content);
}

function arrToXmlLabel($arr, $xmlStr){
        foreach ($arr as $key=>$val){
                if(is_array($val)){
                        $xmlStr.="\n<".$key.">".arrToXmlLabel($val)."</".$key.">";
                }else{
                        $xmlStr.="\n<".$key.">".html_edit($val)."</".$key.">";
                }
        }
        return $xmlStr;
}

function arrToXml($arr){
        $xmlStr='<?xml version="1.0" encoding="UTF-8" ?><env>';
        $xmlStr =arrToXmlLabel($arr, $xmlStr);
        $xmlStr.="\n</env>";
        return $xmlStr;
}



?>
