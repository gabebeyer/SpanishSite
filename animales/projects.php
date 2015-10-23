<!DOCTYPE>
<html>
<head>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<title>Untitled Document</title>
<meta charset="utf-8">
<style type="text/css">
a{text-decoration:none;
 color: #654321;
}
.directory {	
color:white;
background:#AAAAAA;
border: solid; border-width: 1px; border-color:pink; 
padding:0px;
margin:0px;
}
.file{
color: #AAAAAA;
background:pink;
border: solid; border-width: 1px; border-color:#AAAAAA; 
padding:0px;
margin:0px;
}
img{width:20;}

</style>

<?php

$folds[] = array();
$files[] = array();

$dir="";

function listDirs($where){
	    $itemHandler=opendir($where);
	    $i=0;
	    $fo[]= array();
	    $fi[]= array();
	    while(($item=readdir($itemHandler)) !== false){
		if(substr($item, 0, 1)!="." && $item!='projects.php'){
		    if(is_dir($item)){
			array_push($fo,$item);
		//        $dirs .= '<div class=directory><a href='.$item.'/projects.php>'.$item.'</a></div>';
		    } else if(!is_dir($item)){
		//	$dirs .= '<div class=file ><a href='.$item.'>'.$item.'</a><div>';
			array_push($fi,$item);
		    }
		    $i++;
		}
           }
           return array($fo,$fi);
		
}



?>
</head>
<body>
<? 
$d= listDirs("./"); 
$folds = $d[0];
$files = $d[1];


$i=1;
echo '<div class=directory>Folders';
while ($i< count($folds)){
	echo '<div class=directory><a href='.$folds[$i].'/projects.php>'.$folds[$i].'</a></div>';
	$i++;
}
$i=1;
echo '<div class=file>Files';
while ($i< count($files)){
	echo '<div class=file>';
	if(substr($files[$i],-3)=="jpg" ^ substr($files[$i],-3)=="gif"){
		echo '<img src="'.$files[$i].'">';
	
	}
	echo '<a href='.$files[$i].'>   '.$files[$i].'</a>';
	echo '</div>';	

	$i++;
}


/*$i=1;
while ($i< count($files)){
	$i++;
}
*/

?>

</body>
</html>
