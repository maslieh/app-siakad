
<div class="login">
	<div class="loginCell">
		

			<?php
			
			if (!cek_login ()){
			
			?>
			<h2 class="title">User Login</h2>
			<form action="" method="post" name="flogin" id="flogin" >
						<p><input type="text" name="user" id="login_user" class="loginText" value="Username" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;" /></p>
						<p><input type="password" name="password" id="login_pass" class="loginText" value="Password" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;" /></p>
						<input type="hidden" value="1" name="loguser" />
						<p><input type="submit" value="Login" name="submit_login" class="loginButton" /></p>
						</form>
					<div class="loginLink"><a href="?m=lupa">Forgot Password?</a></div>
			<?php
				if (isset ($_POST['submit_login']) ){
					echo web_login ();
				}
				
			} else {
				if ($_SESSION['Level']=="ADMIN"){ //ambil row di kontrol.php
						echo'<div class="cs">
							<div class="csCell">
								<h2 class="title">'.$row['nama'].'</h2>
								<div>
								<img src="'.$avatar.'" class="gambar" height="190" width="180"/>
								</div>
								
								<div class="clear"></div>	
							</div>
						</div>';
				} else {
				if ($_SESSION['Level']=="MAHASISWA"){ 
						echo'<div class="cs">
							<div class="csCell">
								<h2 class="title">'.$row['nama_mahasiswa'].'</h2>
								<div>
								<img src="'.$avatar.'" class="gambar" height="190" width="180"/>
								</div>
								<div class="clear"></div>
									<table  border="0" cellspacing="1" cellpadding="1">
									<thead>
									  <tr>
									  <td>NIM</td>
										<td > <b>'.$row['NIM'].'</b></td>
									  </tr>
									  <tr>
									 
									  <tr>
									  <td>Prodi</td>
										<td> <b >'.viewprodi(''.$row['kode_prodi'].'').'</b ></td>
									  </tr>
									  
									  <tr>
									  <td>Kelas/Smst </td>
										<td> <b >'.viewkelas(''.$row['masuk_kelas'].'').'/ '.Terbilang($row['semester']).'</b ></td>
									  </tr>
									 
									  <tr>
									  <td>P.Akmik </td>
										<td> <b >'.viewdosen($row['pa']).'</b ></td>
									  </tr>
									  
									<tr>
										<td colspan=2><b ><a href="?m=21">Logout</a></b ></td>
									  </tr>						  
									  </thead>
									</table>						
								
								<div class="clear"></div>	
							</div>
						</div>';
				} else {
			
					echo'<div class="cs">
							<div class="csCell">
								<h2 class="title">'.$row['gelar_depan'].' '.$row['nama_dosen'].', '.$row['gelar_belakang'].'</h2>
								<div>
								<img src="'.$avatar.'" class=gambar height="190" width="180"/>
								</div>
								<div class="clear"></div>
									<table  border="0" cellspacing="1" cellpadding="1">
									<thead>
									  <tr>
									  <td>NIP</td>
										<td > : <b>'.$row['NIDN'].'</b></td>
									  </tr>
									  <tr>
									  <td>Jabatan</td>
										<td><b > : '.viewAplikasi('02',''.$row['jabatan_akademik'].'').'</b ></td>
									  </tr>
									  <tr>
									  <td>Pangkat</td>
										<td><b > : '.viewAplikasi('56',''.$row['pangkat_golongan'].'').'</b ></td>
									  </tr>
										 <tr>
										<td colspan=2><b ><a href="?m=37">Logout</a></b ></td>
									  </tr>						  
									  </thead>
									</table>						
								
								<div class="clear"></div>	
							</div>
						</div>';
				}

			} 
		}
			?>
	</div>
</div>
