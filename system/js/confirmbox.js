function callbacks(v,m,f){
    if (v != false)
        window.location= v;
}

function confirm_box(pesan,link){
    $.prompt(pesan,{
        callback: callbacks,
        buttons: {
            Ok: link,
            Batal: false
        }
    });
}

function Checktahun1(){  
    var t1;
    t1=document.inputajaran.tahun1.value;
    t2=document.inputajaran.tahun2.value;
    if(isNaN(t1))
    {
        document.inputajaran.tahun1.value="";

    }
    else if(isNaN(t2))
    {
        document.inputajaran.tahun2.value="";
    }
}

function mySubmitAjaran(v,m,f){
    an = m.children('#tahun');
    if (v == true){
        if(f.nama == ""){
            an.css("border","solid #ff0000 1px");
            return false;
        }else {
            window.location = "index.php?act=raport&do="+f.doo+"&kelas="+f.kelas+"&tahun1="+f.tahun1+"&tahun2="+f.tahun2;
        }
    }
    return true;

}

function tambahAjaran(act,kelas,tahunajaran1,tahunajaran2){
    var txt;
    if (act == "settahunajaran"){
        txt = 'Set tahun ajaran :<br /> <form name="inputajaran" id="inputajaran"><input type="hidden" name="doo" id="doo" value="settahunajaran"> <input type="hidden" name="kelas" id="kelas" value="'+ kelas+'"> <input type="text" id="tahun1" name="tahun1" size="4" value="' + tahunajaran1 +'" onKeyUp="Checktahun1()"/> / <input type="text" id="tahun2" size="4" name="tahun2" onKeyUp="Checktahun1()" value="' + tahunajaran2 +'" /></form>';
    }
    $.prompt(txt,{
        submit: mySubmitAjaran,
        buttons: {
            Ok:true,
            Batal:false
        }
    });
}
function toggleView(id){
    $('#'+id).toggle('fast');
    return false;
}