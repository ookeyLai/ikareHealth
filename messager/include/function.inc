<?php
define("LIBROOT","lib/");

session_start();

if (!defined("FUNCTION")) {

if (!isset($vars)) $vars=array();

if (count($vars)) {
  while (list($k,$v)=each($vars)) {
    $$k = $v;
  }
}

$languages = array("en","tw");
$lang_str = array("en"=>"English","tw"=>"繁體中文");

$level_color= array("","lightblue","lightyellow","pink");
$tmcolors= array("","lightblue","lightyellow","pink");
$status_color= array("","lightgreen","#CCCCFF","#CCCCCC","pink");

function hide_vars()
{
   global $vars;

   while (list($key, $value) = each($vars)) {
      if ($key != "login" && $key != "error")
         echo "<INPUT TYPE=hidden NAME=$key VALUE=$value>\n";
   }
   reset($vars);
}

function backToPage($p) {
	echo "<script language='javascript'>";
	echo "document.location='index.php?page=$p';";
	echo "</script>";
}

function check_perm($perms)
{
  global $auth;

  // Parse all permissions in argument, comma separated
  // It is assumed auth_user only has one group per user.
  if (!$auth["perms"]) return False;
  if ($perms == "none" || !$perms) {
     return True;
  }
  else {
    $p1 = explode(",", $auth["perms"]);
    $p2 = explode(",", $perms);
    while (list($key1, $value1) = each($p1)) {
      while (list($key2, $value2) = each($p2)) {
        if ($value1 == $value2) {
          return True;
        }
      }
    }
    if (count($p1)==1 && count($p2)==1) {
        return ($p1[0]<=$p2[0])?True:False;
    }
  }
  return False;
}

function load_class($classname)
{
  if (!defined("$classname")) {
    include(LIBROOT.$classname.".inc");
    define("$classname","1");
  }
  $str = "\$".$classname." = new ".$classname.";";
  eval($str);
  return $$classname;
}

function keyword_SQL($keyword, $fields, $cates="") {
  $keyword = preg_replace("/([\(\)|&])/"," \\1 ",$keyword);
  $k = preg_split("/[,\s]+/",trim($keyword));
  $ret = "";
  for ($i=0;$i<count($k);$i++) {
    if (strtoupper($k[$i]) == 'OR') { $op = 'OR'; continue; }
    if (($k[$i]) == '|') { $op = 'OR'; continue; }
    if (strtoupper($k[$i]) == 'AND') { $op = 'AND'; continue; }
    if (($k[$i]) == '&') { $op = 'AND'; continue; }
    if (($k[$i]) == '(') { $ret .= "$op ( "; $op=""; continue; }
    if (($k[$i]) == ')') { $ret .= " ) "; $op="AND"; continue; }
    $ret .= $op . " ( ";
    $k[$i] = addslashes($k[$i]);
    for ($j=0;$j<count($fields);$j++) {
      $pp = substr($fields[$j],0,1);
      if ($pp=='=' || $pp=='<' || $pp=='>' || $pp=='%') {
        $ff = substr($fields[$j],1);
      }
      else {
        $ff = $fields[$j];
      }
      if (is_array($cates) && $cates[$ff]) {
        $ref_code = $cat->get_code($cates[$ff],$k[$i],"code");
        if ($ref_code) {
          if ($pp == '%')
            $ret .= $op1." ".$ff." LIKE '%,".$ref_code.",%' ";
          else
            $ret .= $op1." ".$ff." = '$ref_code' ";
        }
      }
      else if ($pp=='=' || $pp=='<' || $pp=='>') {
        $ret .= $op1." ".$ff." $pp '".$k[$i]."' ";
      }
      else {
        if (BINARY_MATCH)
          $ret .= $op1." ".$ff." LIKE BINARY '%".$k[$i]."%' ";
        else
          $ret .= $op1." ".$ff." LIKE '%".$k[$i]."%' ";
      }
      $op1 = "OR";
    }
    $ret .= " ) ";
    $op = "AND";
    $op1 = "";
  }
  if ($ret) $ret = " ( ".$ret." ) ";
  return $ret;
}

function keyword_SQL_OR($keyword, $fields, $cates="") {
  $keyword = preg_replace("/([\(\)|&])/"," \\1 ",$keyword);
  $k = preg_split("/[,\s]+/",trim($keyword));
  $cat = load_class("category");
  $ret = "";
  for ($i=0;$i<count($k);$i++) {
    if (strtoupper($k[$i]) == 'OR') { $op = 'OR'; continue; }
    if (($k[$i]) == '|') { $op = 'OR'; continue; }
    if (strtoupper($k[$i]) == 'AND') { $op = 'OR'; continue; }
    if (($k[$i]) == '&') { $op = 'OR'; continue; }
    if (($k[$i]) == '(') { $ret .= "$op ( "; $op=""; continue; }
    if (($k[$i]) == ')') { $ret .= " ) "; $op="OR"; continue; }
    $ret .= $op . " ( ";
    $k[$i] = addslashes($k[$i]);
    for ($j=0;$j<count($fields);$j++) {
      $pp = substr($fields[$j],0,1);
      if ($pp=='=' || $pp=='<' || $pp=='>' || $pp=='%') {
        $ff = substr($fields[$j],1);
      }
      else {
        $ff = $fields[$j];
      }
      if (is_array($cates) && $cates[$ff]) {
        $ref_code = $cat->get_code($cates[$ff],$k[$i],"code");
        if ($ref_code) {
          if ($pp == '%')
            $ret .= $op1." ".$ff." LIKE '%,".$ref_code.",%' ";
          else
            $ret .= $op1." ".$ff." = '$ref_code' ";
        }
      }
      else if ($pp=='=' || $pp=='<' || $pp=='>') {
        $ret .= $op1." ".$ff." $pp '".$k[$i]."' ";
      }
      else {
        if (BINARY_MATCH)
          $ret .= $op1." ".$ff." LIKE BINARY '%".$k[$i]."%' ";
        else
          $ret .= $op1." ".$ff." LIKE '%".$k[$i]."%' ";
      }
      $op1 = "OR";
    }
    $ret .= " ) ";
    $op = "OR";
    $op1 = "";
  }
  if ($ret) $ret = " ( ".$ret." ) ";
  return $ret;
}

function search_header($title, $modulename, $pagename, $period="") {
  global $sess, $print;
  if ($print) return;

  $header = "<TABLE WIDTH=\"100%\" BORDER=0 CELLSPACING=0 CELLPADDING=5 class=BlackText>\n";
  $header .= "<TR>\n";
  $header .= "<TD NOWRAP ALIGN=LEFT class=BlackText>$title :</TD>\n";
  $header .= "<TD COLSPAN=3 ALIGN=RIGHT>\n";
  $header .= "<FORM NAME=Search ACTION='" . $WEBROOT . "' METHOD=POST>\n";
  if ($period) {
    $header .=" Period:";
    $header .= "<INPUT TYPE=TEXT SIZE=8 NAME=period1>";
    $header .= "<a href=\"javascript:show_calendar('Search.period1');\" onmouseover=\"window.status='Date Picker';return true;\" onmouseout=\"window.status='';return true;\"><img src=\"".IMGROOT."show-calendar.gif\" width=24 height=22 border=0></a>";
    $header .= " - ";
    $header .= "<INPUT TYPE=TEXT SIZE=8 NAME=period2>";
    $header .= "<a href=\"javascript:show_calendar('Search.period2');\" onmouseover=\"window.status='Date Picker';return true;\" onmouseout=\"window.status='';return true;\"><img src=\"".IMGROOT."show-calendar.gif\" width=24 height=22 border=0></a>";
    $header .=" &nbsp;Keyword:";
  }
  $header .= "<INPUT TYPE=TEXT SIZE=16 NAME=keyword>\n";
  $header .= "<INPUT TYPE=HIDDEN NAME=page VALUE=". $modulename . "/" . $pagename . ">\n";
  $header .= "<INPUT TYPE=Submit Name=Search Value='Search'>\n";
  echo $header;
  $sess->hidden_session();
  $header = "</FORM>\n";
  $header .= "</TD></TR>\n";
  $header .= "</TABLE>\n";
  echo $header;
}

function search_header1($title, $modulename, $pagename, $period="",$cid="") {
  global $sess, $print;
  if ($print) return;

  $header = "<TABLE WIDTH=\"100%\" BORDER=0 CELLSPACING=0 CELLPADDING=0 class=BlackText>\n";
  $header .= "<TR>\n";
  $header .= "<TD NOWRAP ALIGN=LEFT><font size=+1>$title</font></TD>\n";
  $header .= "<TD ALIGN=RIGHT>\n";
  $header .= "<FORM NAME=Search ACTION='" . $WEBROOT . "' METHOD=POST>\n";
  if ($period) {
    $header .=" Period:";
    $header .= "<INPUT TYPE=TEXT SIZE=8 NAME=period1>";
    $header .= "<a href=\"javascript:show_calendar('Search.period1');\" onmouseover=\"window.status='Date Picker';return true;\" onmouseout=\"window.status='';return true;\"><img src=\"".IMGROOT."show-calendar.gif\" width=24 height=22 border=0></a>";
    $header .= " - ";
    $header .= "<INPUT TYPE=TEXT SIZE=8 NAME=period2>";
    $header .= "<a href=\"javascript:show_calendar('Search.period2');\" onmouseover=\"window.status='Date Picker';return true;\" onmouseout=\"window.status='';return true;\"><img src=\"".IMGROOT."show-calendar.gif\" width=24 height=22 border=0></a>";
    $header .=" &nbsp;Keyword:";
  }
  if ($cid) {
    $header .= "<INPUT TYPE=HIDDEN NAME=company_id VALUE='$cid'>\n";
  }
  $header .= "<INPUT TYPE=TEXT SIZE=16 NAME=keyword>\n";
  $header .= "<INPUT TYPE=HIDDEN NAME=page VALUE=". $modulename . "/" . $pagename . ">\n";
  $header .= "<INPUT TYPE=Submit Name=Search Value='Search'>\n";
  $header .= $sess->hidden_session1();
  $header .= "<INPUT TYPE=HIDDEN NAME=offset VALUE='0'>\n";
  $header .= "</FORM>\n";
  $header .= "</TD></TR>\n";
  $header .= "</TABLE>\n";
  return $header;
}

function search_footer($modulename, $pagename, $offset, $num_rows, $keyword, $extra="") {
  global $sess;
  $footer = "<TABLE WIDTH=\"300\" BORDER=0 CELLSPACING=0 CELLPADDING=5>\n";
  $footer .= "<TR><TD WIDTH=60 NOWRAP ALIGN=RIGHT class=BlackText>\n";
  // Check to see if we need to have previous button
  if ($keyword) $keyword = urlencode($keyword);
  // if ($extra) $extra = urlencode($extra);
  if ($offset >= SEARCH_ROWS) {
    $prevoffset=$offset-SEARCH_ROWS;
    $footer .= "<A HREF=";
    $footer .= $sess->furl($WEBROOT . "?page=$modulename/$pagename&keyword=$keyword&offset=$prevoffset$extra");
    $footer .= ">Prev</A>&nbsp;\n";
   }
   else
    $footer .= "&nbsp;";
   $footer .= "</TD><TD class=BlackText>";
   // Get total pages
   if ($num_rows > MAX_IDS && $modulename != 'admin') {
     $footer .= "<font color=red>Limit ".MAX_IDS."($num_rows) records</font><br>";
     $num_rows = MAX_IDS; //Limit Search Result Records
   }
   $num_pages = intval($num_rows / SEARCH_ROWS);
   if ($num_rows % SEARCH_ROWS) {
      $num_pages++;
   }
   if ($num_pages != 1) {
     $n_len = strlen($num_pages);
     for ($i=1;$i<=$num_pages;$i++) {
        $pg = sprintf("%0".$n_len."d",$i);
        $newoffset = SEARCH_ROWS * ($i-1);
        if (($offset < $i*SEARCH_ROWS) && ($offset >= ($i-1)*SEARCH_ROWS)) {
           $pagenumber = "<FONT color=red>$pg</FONT>";
        }
        else
           $pagenumber = "<A HREF=".$sess->furl($WEBROOT . "?page=$modulename/$pagename&offset=$newoffset&keyword=$keyword$extra").">$pg</A>";
        $footer .= $pagenumber."&nbsp;";
        if ($i%20==0) $footer .= "<br>";
     }
   }
   $footer .= "</TD><TD ALIGN=LEFT width=60 nowrap class=BlackText>";
   if (($offset+SEARCH_ROWS < $num_rows) && $num_pages != 1) {
      $newoffset = $offset + SEARCH_ROWS;
      $footer .= "<A HREF=";
      $footer .= $sess->furl($WEBROOT . "?page=$modulename/$pagename&offset=$newoffset&keyword=$keyword$extra");
      $footer .=  ">Next</A>\n";
   }
   else
    $footer .= "&nbsp;";
   $footer .= "</TD></TR>";
   $footer .= "<tr height=10><td></td><td class=BlackText>Total $num_rows Record(s)</td></TR></TABLE>";
   echo $footer;
}

function web_search_footer($next_page, $offset, $num_rows, $keyword, $extra="",$s_rows="") {
  global $sess;
  if ($s_rows) $SEARCH_ROWS = $s_rows;
  else $SEARCH_ROWS = SEARCH_ROWS;
  $footer = "<TABLE WIDTH=\"430\" BORDER=0 ALIGN=CENTER CELLSPACING=0 CELLPADDING=5>\n";
  $footer .= "<TR><TD WIDTH=150 NOWRAP ALIGN=RIGHT class='text'>\n";
  if ($keyword) $keyword = urlencode($keyword);
  // if ($extra) $extra = urlencode($extra);
  if ($offset >= $SEARCH_ROWS) {
    $prevoffset=$offset-$SEARCH_ROWS;
    $footer .= "<A HREF=";
    $footer .= $sess->furl("$next_page?keyword=$keyword&offset=$prevoffset$extra");
    $footer .= ">&lt;&lt;Prev</A>&nbsp;\n";
   }
   else
    $footer .= "&nbsp;";
   $footer .= "</TD><TD nowrap align=center class='text'>";
   // Get total pages
   $num_pages = intval($num_rows / $SEARCH_ROWS);
   if ($num_rows % $SEARCH_ROWS) {
      $num_pages++;
   }
   if ($num_pages > 1) {
     $n_len = strlen($num_pages);
     $footer .= "<FORM id=npage name=npage action='".$sess->furl("$next_page?keyword=$keyword$extra")."' method=POST>";
     $footer .= "Page: <SELECT name=offset onChange='npage.submit();'>";
     for ($i=1;$i<=$num_pages;$i++) {
        $pg = sprintf("%0".$n_len."d",$i);
        if (($offset < $i*$SEARCH_ROWS) && ($offset >= ($i-1)*$SEARCH_ROWS)) {
           $sel = "SELECTED";
        }
        else
           $sel = "";
        $newoffset = $SEARCH_ROWS * ($i-1);
        $footer .= "<OPTION value=$newoffset $sel>$pg</OPTION>";
     }
     $footer .= "</SELECT>";
   }
   $footer .= "</TD><TD ALIGN=LEFT width=150 nowrap class='text'>";
   if (($offset+$SEARCH_ROWS < $num_rows) && $num_pages != 1) {
      $newoffset = $offset + $SEARCH_ROWS;
      $footer .= "<A HREF=";
      $footer .= $sess->furl("$next_page?offset=$newoffset&keyword=$keyword$extra");
      $footer .=  ">Next&gt;&gt;</A>\n";
   }
   else
    $footer .= "&nbsp;";
   $footer .= "</TD></TR>";
   $footer .= "<tr height=10><td align=center colspan=3 class=text>";
   if ($num_rows>0) $footer .= "Total $num_rows Record(s)";
   if ($num_pages>1) $footer .= "/ $num_pages Pages";
   $footer .= "</td></TR>";
   $footer .= "</TABLE>";
   echo $footer;
}

function inserttmp($filename,$refilename,$id_number,$cand_id='')
{
   $db = new ps_DB;
   $create_date = date("y-m-d");
   $insert_sql = "INSERT INTO attachmentTEMP (id_number,candidate_id,create_date,filename,refilename) VALUES ('$id_number','$cand_id','$create_date','$filename','$refilename')";
   $db->query($insert_sql);
}
function DeleteImg($id,$delname)
{
   $DesFile = ATT_ROOT.$delname;
   if(@unlink($DesFile))
   {
      $db = new ps_DB;
      $qq = "DELETE FROM attachment WHERE attachment_id=$id";
      $db->query($qq);
      echo "Delete $delname Completed";
   }else{
      echo "Delete $delname False";
   }
}
function ChangeChar($char)
{
  switch ($char)
  {
     case "eq":
        return "=";
     case "le":
       return "<=";
     case "ge":
       return ">=";
   }
}

function datestr($s) {
  if ($s && strpos($s,"/")===False) {
    $y = substr($s,0,4);
    $m = substr($s,4,2);
    $d = substr($s,6,2);
    $str = "$y/$m";
    if ($d) $str .= "/$d";
    return $str;
  } 
  return $s;
}

function a_email($email) {
  $ret = "";
  if (strpos($email,"@")) {
    $emailimg = "<img height=17 alt='$email' src='".IMGROOT."email.gif' border=0>";
    $ret = "<a href='mailto:$email'>$emailimg</a>";
  }
  return $ret;
}

function a_web($web,$name="") {
  if (!$web) return "";
  if (!strstr($web,"http://")) {
    $web = "http://$web";
  }
  $webimg = "<img alt='$web' width=17 height=17 src='".IMGROOT."web.gif' border=0>";
  if ($name) $webimg = $name;
  if ($name=="_") $webimg = $web;
  $ret = "<a href='$web' target=_blank>$webimg</a>";
  return $ret;
}

function list_page($list_page_array,$now,$extra_str)
{
   echo "<div align=center><table><tr>";
   while(list($key, $val) = each($list_page_array))
   {
      if($now == $val)
         echo "<td><a href='?page=candidate/show&func=search_person/search&showone=yes&id=$val$extra_str'>
              <font color=green size=6><b>$key</b></font>
              </a></td>";
      else
         echo "<td><a href='?page=candidate/show&func=search_person/search&showone=yes&id=$val$extra_str'>$key</a><td>";
   }
   echo "<tr></table></div>";
}

function index_page($modulename, $pagename, $ids, $idx, $f="idx",$extra="") {
  global $sess;
  $s = "<table width=260 border=0 align=center><tr><td align=left width=80>";
  if ($idx > 1) {
    $previdx=$idx-1;
    $s .= "<A HREF=";
    $s .= $sess->furl($WEBROOT . "?page=$modulename/$pagename&$f=$previdx$extra");
    $s .= ">&lt;&lt;&nbsp;Prev</A>\n";
  }
  else
    $s .= "&nbsp;";
  $s .= "</td><td align=center width=100>";
  $s .= "<b>$idx / ".$ids[0]."</b>";
  $s .= "</td><td align=right width=80>";
  if ($idx < count($ids)-1) {
    $nextidx=$idx+1;
    $s .= "<A HREF=";
    $s .= $sess->furl($WEBROOT . "?page=$modulename/$pagename&$f=$nextidx$extra");
    $s .= ">Next&nbsp;&gt;&gt;</A>\n";
  }
  else
    $s .= "&nbsp;";
  $s .= "</td></tr></table>";
  return $s;
}

function list_country($list_name, $value="") {
  $db = new ps_DB;

  $q = "SELECT * from country ORDER BY country_name ASC";
  $db->query($q);
  $ret = "<SELECT NAME=$list_name class='select'>\n";
  $ret .= "<OPTION VALUE=\"\"> - </OPTION>\n";
  while ($db->next_record()) {
    $ret .= "<OPTION VALUE=" . $db->f("country_3_code");
    if ($value == $db->f("country_3_code")) {
      $ret .= " SELECTED";
    }
    $ret .= ">" . $db->f("country_name") . "</OPTION>\n";
  }
  $ret .= "<SELECT>\n";
  return $ret;
}

function sh_avg($arr) {
	return array_sum($arr) / count(array_filter($arr));
}

function sh_sd($arr, $avg, $sample=1) {
	$n = count($arr);
	$sum = 0;
	for ($i=0;$i<$n;$i++) {
		$sum += ($arr[$i]-$avg)*($arr[$i]-$avg);
	}
	return $n>1?sqrt($sum/($n-$sample)):0;
}

define("FUNCTION",true);

} //END define FUNCTION

?>
