<script language="javascript">
function newWindow(mypage,myname,w,h,features) {
      var winl = (screen.width-w)/2;
      var wint = (screen.height-h)/2;
      if (winl < 0) winl = 0;
      if (wint < 0) wint = 0;
      var settings = 'height=' + h + ',';
      settings += 'width=' + w + ',';
      settings += 'top=' + wint + ',';
      settings += 'left=' + winl + ',';
      settings += features;
      win = window.open(mypage,myname,settings);
      win.window.focus();
}

function bukachat(){
	newWindow('aplikasi/chat.php?user=<?php echo $user;?>', 'Ruang Chatting','500','500','resizable=0,scrollbars=0,status=0,toolbar=0')
}

</script>
<center><a href="javascript:bukachat()"><img src="images/live-chat.gif"></a></center>


