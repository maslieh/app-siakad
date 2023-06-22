<?php
/*
Developed by Reneesh T.K
reneeshtk@gmail.com
You can use it with out any worries...It is free for you..It will display the out put like:
First | Previous | 3 | 4 | 5 | 6 | 7| 8 | 9 | 10 | Next | Last
Page : 7  Of  10 . Total Records Found: 20
*/
 
class Pagination_class{
	var $result;
	var $anchors;
	var $total;
	
	function __construct($koneksi_db, $qry, $starting, $recpage)
	{
		$rst		=	$koneksi_db->sql_query($qry);
		$numrows	=	$koneksi_db->sql_numrows($rst);
		$qry		 .=	" limit $starting, $recpage";
		$this->result	=	$koneksi_db->sql_query($qry);
		$next		=	$starting+$recpage;
		$var		=	((intval($numrows/$recpage))-1)*$recpage;
		$page_showing	=	intval($starting/$recpage)+1;
		$total_page	=	ceil($numrows/$recpage);

		if($numrows % $recpage != 0){
			$last = ((intval($numrows/$recpage)))*$recpage;
		}else{
			$last = ((intval($numrows/$recpage))-1)*$recpage;
		}
		$previous = $starting-$recpage;
		$anc = "<div class=\"\"><ul class=\"pagination \" id='pagination'>";
		if($previous < 0){
			$anc .= "<li class='active'><a href=\"#\">First</a></li>";
			$anc .= "<li class='active'><a href=\"#\">Previous</a></li>";
		}else{
			$anc .= "<li class=' '><a href='javascript:pagination(0);'>First </a></li>";
			$anc .= "<li class=' '><a href='javascript:pagination($previous);'>Previous </a></li>";
		}
		
		################If you dont want the numbers just comment this block###############	
		$norepeat = 2;//no of pages showing in the left and right side of the current page in the anchors 
		$j = 1;
		$anch = "";
		for($i=$page_showing; $i>1; $i--){
			$fpreviousPage = $i-1;
			$page = ceil($fpreviousPage*$recpage)-$recpage;
			$anch = "<li><a href='javascript:pagination($page);'>$fpreviousPage </a></li>".$anch;
			if($j == $norepeat) break;
			$j++;
		}
		$anc .= $anch;
		$anc .= "<li class='active'><a href=\"#\">".$page_showing."</a></li>";
		$j = 1;
		for($i=$page_showing; $i<$total_page; $i++){
			$fnextPage = $i+1;
			$page = ceil($fnextPage*$recpage)-$recpage;
			$anc .= "<li><a href='javascript:pagination($page);'>$fnextPage</a></li>";
			if($j==$norepeat) break;
			$j++;
		}
		############################################################
		if($next >= $numrows){
			$anc .= "<li class='active'><a href=\"#\">Next</a></li>";
			$anc .= "<li class='active'><a href=\"#\">Last</a></li>";
		}else{
			$anc .= "<li class=' '><a href='javascript:pagination($next);'>Next </a></li>";
			$anc .= "<li class=' '><a href='javascript:pagination($last);'>Last</a></li>";
		}
			$anc .= "</ul>";
		$this->anchors = $anc;
		$this->total = "Halaman : <b>$page_showing</b> dari <b>$total_page</b>. Total Data : <b>$numrows</b>";
	}
}
?>
