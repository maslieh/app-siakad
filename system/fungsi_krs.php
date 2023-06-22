<script>
<?php
global $koneksi_db;
				
echo "var jumlah = ".count($kode).";\n";
echo "var sks = new Array();\n";
echo "var boleh= ".$wsks[jml_sks].";\n";
//mengambil sks matakuliah dan memasukkan ke array javascript
for($j=0;$j<count($kode);$j++){
    echo "sks['".$kode[$j]."'] = ".$sks[$j].";\n";
}
?>
//boleh=document.getElementById(bolehsks).value;

function hitungtotal(){

    jum = 0;
    for(i=0;i<jumlah;i++){
        id = "ambil"+i;
        td1 = "k1"+i;
        td2 = "k2"+i;
        td3 = "k3"+i;
        td4 = "k4"+i;
		td5 = "k5"+i;
		td6 = "k6"+i;
		td7 = "k7"+i;
		td8 = "k8"+i;
        if(document.getElementById(id).checked){
            kode = document.getElementById(id).value
            jum = jum + sks[kode];
            //untuk mengubah warna latar tabel apabila diceklist
            document.getElementById(td1).style.backgroundColor = "lightblue";
            document.getElementById(td2).style.backgroundColor = "lightblue";
            document.getElementById(td3).style.backgroundColor = "lightblue";
            document.getElementById(td4).style.backgroundColor = "lightblue";
			document.getElementById(td5).style.backgroundColor = "lightblue";
			document.getElementById(td6).style.backgroundColor = "lightblue";
			document.getElementById(td7).style.backgroundColor = "lightblue";
			document.getElementById(td8).style.backgroundColor = "lightblue";
			
				if (jum > boleh)
				{
					document.getElementById(id).checked=false;
					alert("Jumlah SKS yg diambil melebihi batas maksimal");
					callback(); 
					return false;
					
				}
        }else {
            document.getElementById(td1).style.backgroundColor = "";
            document.getElementById(td2).style.backgroundColor = "";
            document.getElementById(td3).style.backgroundColor = "";
            document.getElementById(td4).style.backgroundColor = "";
			document.getElementById(td5).style.backgroundColor = "";
			document.getElementById(td6).style.backgroundColor = "";
			document.getElementById(td7).style.backgroundColor = "";
			document.getElementById(td8).style.backgroundColor = "";
        }
    }
    //menampilkan total jumlah SKS yang diambil
    document.getElementById("jsks").innerHTML = jum;
}



</script>	
