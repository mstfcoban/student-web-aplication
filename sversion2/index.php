<?php
require "databaseconnection.php";

pageHeader();
listele($conn);
jsKodu($conn);
pageBottom();
$conn->close();
exit;

function pageHeader(){
  echo "
      <!DOCTYPE html>
      <html>
        <meta charset='utf-8'>
        <head>
        <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css'>
        </head>
        <style>
          table,th,td {
                      border:2px solid black;
                      border-collapse: collapse;
                      padding: 15px;
                      text-align: center;
                      margin-left: auto; 
                      margin-right: auto;
                      margin-top: 100px;
                    }
          th:hover {background-color: #f2f2f2;}
          tbody tr:nth-child(odd) {background-color: #f2f2f2;}
        </style>
        <body>";
}

function listele($conn){
  $sql_select = "SELECT * FROM student ";
  $order = "ORDER BY ";
  $sutun = "sid";
  $artanAzalan = "ASC";

  $sayfa = isset($_GET['sayfa']) ? mysqli_real_escape_string($conn,$_GET['sayfa']) : 1;
  $goster = 5;
  $sayi = ($sayfa-1)*$goster;
  $limit = "LIMIT ".$sayi.",".$goster;
  $sonsayfa = ceil(mysqli_num_rows(mysqli_query($conn, $sql_select))/$goster);

  $artanAzalan = @ $_GET['order']=="ASC" ? "DESC" : "ASC";
  $sutun = @ $_GET['sutun'] ? mysqli_real_escape_string($conn,$_GET['sutun']) : "sid";
  $artanAzalan = isset($_GET['sirala']) ? mysqli_real_escape_string($conn,$_GET['sirala']) : $artanAzalan;

  echo <<<LIST
            <table style='width:80%' id="myTable">
            <thead>
              <tr >
                <th style='width:10%'><a href="?op=sirala&sutun=sid&order=$artanAzalan&sayfa=$sayfa">No</a></th>
                <th style='width:15%'><a href="?op=sirala&sutun=fname&order=$artanAzalan&sayfa=$sayfa">Ad</a></th>
                <th style='width:15%'><a href="?op=sirala&sutun=lname&order=$artanAzalan&sayfa=$sayfa">Soyad</a></th>
                <th style='width:15%'><a href="?op=sirala&sutun=birthplace&order=$artanAzalan&sayfa=$sayfa">Doğum Yeri</a></th>
                <th style='width:15%'><a href="?op=sirala&sutun=birthdate&order=$artanAzalan&sayfa=$sayfa">Doğum Tarihi</a></th>
                <th style='width:15%'><input class="btn btn-default" onclick='yeni()' id='yeni' type='submit' name='yeni' value='YENİ'></th>
                <th style='width:20%'><input class="btn btn-default" onclick='ara()' id='ara' type='submit' name='ara' value='ARA'></th>
              </tr>
            </thead>
            <tbody id='tbody'> 
  LIST;

  $result = mysqli_query($conn, $sql_select.$order.$sutun." $artanAzalan ".$limit);
    while($row = mysqli_fetch_assoc($result)) {
      echo "
        <tr id='$row[sid]'>
          <td id='0$row[sid]'>".$row['sid']."</td>
          <td id='$row[fname]$row[sid]'>".$row['fname']."</td>  
          <td id='$row[lname]$row[sid]'>".$row['lname']."</td>
          <td id='$row[birthplace]$row[sid]'>".$row['birthplace']."</td>
          <td id='$row[birthdate]$row[sid]'>".$row['birthdate']."</td>
          <td><input class='btn btn-default' onclick='sil($row[sid])' id='sil' data-id='$row[sid]' type='submit' name='sil' value='SİL'></td>
          <td id='$row[sid]guncelle'><input class='btn btn-default' onclick='guncelle($row[sid])' id='guncelle' data-fname='$row[fname]' 
                                      data-lname='$row[lname]' data-bplace='$row[birthplace]' data-bdate='$row[birthdate]'
                                      type='submit' name='guncelle' value='GÜNCELLE'></td>
        </tr>";
    } 
  echo "</tbody>
        </table>";

  echo "<center style='margin-top:10px'>";
    $gosterilenSayfaSayisi = 7;
    $ortasayfa = $sayfa;
    $ortaSayfaEnAz = ceil($gosterilenSayfaSayisi/2);
    $ortaSayfaEnCok = $sonsayfa-$ortaSayfaEnAz+1;

    if($ortasayfa < $ortaSayfaEnAz){
      $ortasayfa = $ortaSayfaEnAz;
    }
    if($ortasayfa > $ortaSayfaEnCok){
      $ortasayfa = $ortaSayfaEnCok;
    }
    $solSayfalar = round($ortasayfa-(($gosterilenSayfaSayisi-1)/2));
    $sagSayfalar = round((($gosterilenSayfaSayisi-1)/2)+$ortasayfa);
    if($solSayfalar < 1){
      $solSayfalar = 1;
    }
    if($sagSayfalar > $sonsayfa){
      $sagSayfalar = $sonsayfa;
    }
    if($sayfa != 1){
      echo '<a href="?sayfa=1&sutun='.$sutun.'&sirala='.$artanAzalan.'">'."<<".'</a>'." ";
      echo '<a href="?sayfa='.($sayfa-1).'&sutun='.$sutun.'&sirala='.$artanAzalan.'">'."<".'</a>'." ";
    }
    else{
      echo "<< ";
      echo "< ";
    }
    for($i=$solSayfalar; $i <= $sagSayfalar; $i++) {
      echo $sayfa == $i ? $i." " : '<a href="?sayfa='.$i.'&sutun='.$sutun.'&sirala='.$artanAzalan.'">'.$i.'</a>'." ";
    }
    if($sayfa != $sonsayfa){
      echo '<a href="?sayfa='.($sayfa+1).'&sutun='.$sutun.'&sirala='.$artanAzalan.'">'.">".'</a>'." ";
      echo '<a href="?sayfa='.($sonsayfa).'&sutun='.$sutun.'&sirala='.$artanAzalan.'">'.">>".'</a>';
    }
    else{
      echo " >";
      echo " >>";  
    }
    echo "</center>";
}

function jsKodu(){
?>
  <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>

  <script>
    function yeni(){
        if(document.getElementById('olustur')==null){
          var table = document.getElementById("myTable");
          var row = table.insertRow(1);
          var cell1 = row.insertCell(0);
          var cell2 = row.insertCell(1);
          var cell3 = row.insertCell(2);
          var cell4 = row.insertCell(3);
          var cell5 = row.insertCell(4);
          var cell6 = row.insertCell(5);
          var cell7 = row.insertCell(6);

          row.setAttribute('id','row-yeni');

          cell2.innerHTML = "<input type='text' style='width:100%' name='fname'>";
          cell3.innerHTML = "<input type='text' style='width:100%' name='lname'>";
          cell4.innerHTML = "<input type='text' style='width:100%' name='birthplace'>";
          cell5.innerHTML = "<input type='date' style='width:100%' name='birthdate'>";
          cell6.innerHTML = "<input class='btn btn-default' onclick='olustur()' id='olustur' type='submit' name='olustur' value='OLUŞTUR'>";
          cell7.innerHTML = "";

          if(document.getElementById("row-bul")){document.getElementById("row-bul").remove();}
        }
        else{
          var row = document.getElementById('row-yeni');
          row.remove();
        }
    }

    function olustur(){
      var name = $("input[name=fname]").val();
      var lname = $("input[name=lname]").val();
      var bplace = $("input[name=birthplace]").val();
      var bdate = $("input[name=birthdate]").val();

      if(name=="" || lname=="" || bplace=="" || bdate==""){
        alert("AD, SOYAD, DOĞUM YERİ veya DOĞUM TARİHİNİ eksik girdiniz.");
      }
      else{
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
          var data = this.responseText;
          document.getElementById('row-yeni').remove();
            alert('Yeni kayıt oluşturuldu.');

            var table = document.getElementById("myTable");
            var row = table.insertRow(1);
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);
            var cell4 = row.insertCell(3);
            var cell5 = row.insertCell(4);
            var cell6 = row.insertCell(5);
            var cell7 = row.insertCell(6);
              
            row.setAttribute('id',data);
            cell1.setAttribute('id',+'0'+data);
            cell2.setAttribute('id',name+data);
            cell3.setAttribute('id',lname+data);
            cell4.setAttribute('id',bplace+data);
            cell5.setAttribute('id',bdate+data);
            cell7.setAttribute('id',data+'guncelle');

            cell1.innerHTML = data;
            cell2.innerHTML = name;
            cell3.innerHTML = lname;
            cell4.innerHTML = bplace;
            cell5.innerHTML = bdate;
            cell6.innerHTML = "<input onclick='sil("+data+")' id='sil' data-id="+data+" type='submit' name='sil' value='SİL'>";
            cell7.innerHTML = "<input onclick='guncelle("+data+")' id='guncelle' data-fname="+name+" data-lname="+lname+" data-bplace="+bplace+
                                                    " data-bdate="+bdate+" type='submit' name='guncelle' value='GÜNCELLE'>";
        }
        else if(this.status==403){
          alert("Veri başarıyla eklenemedi.");
        }
      };
      xmlhttp.open("GET", "yeni.php?fname=" +name+"&lname="+lname+"&birthplace="+bplace+"&birthdate="+bdate, true);
      xmlhttp.send();

        // $.ajax({
        //   type: "GET",
        //   url : "yeni.php",
        //   data: {"fname":name,
        //          "lname":lname,
        //          "birthplace":bplace,
        //          "birthdate":bdate},
        //   success: function(data){
        //     $("#row-yeni").remove();
        //     alert('Yeni kayıt oluşturuldu.');

        //     var table = document.getElementById("myTable");
        //     var row = table.insertRow(1);
        //     var cell1 = row.insertCell(0);
        //     var cell2 = row.insertCell(1);
        //     var cell3 = row.insertCell(2);
        //     var cell4 = row.insertCell(3);
        //     var cell5 = row.insertCell(4);
        //     var cell6 = row.insertCell(5);
        //     var cell7 = row.insertCell(6);
              
        //     row.setAttribute('id',data);
        //     cell1.setAttribute('id',+'0'+data);
        //     cell2.setAttribute('id',name+data);
        //     cell3.setAttribute('id',lname+data);
        //     cell4.setAttribute('id',bplace+data);
        //     cell5.setAttribute('id',bdate+data);
        //     cell7.setAttribute('id',data+'guncelle');

        //     cell1.innerHTML = data;
        //     cell2.innerHTML = name;
        //     cell3.innerHTML = lname;
        //     cell4.innerHTML = bplace;
        //     cell5.innerHTML = bdate;
        //     cell6.innerHTML = "<input onclick='sil("+data+")' id='sil' data-id="+data+" type='submit' name='sil' value='SİL'>";
        //     cell7.innerHTML = "<input onclick='guncelle()' id='guncelle' data-id="+data+
        //                                             " data-fname="+name+" data-lname="+lname+" data-bplace="+bplace+
        //                                             " data-bdate="+bdate+" type='submit' name='guncelle' value='GÜNCELLE'>";
        //   }
        // });
      }
    }

    function ara(){
        if(document.getElementById('bul')==null){
          var table = document.getElementById("myTable");
          var row = table.insertRow(1);
          var cell1 = row.insertCell(0);
          var cell2 = row.insertCell(1);
          var cell3 = row.insertCell(2);
          var cell4 = row.insertCell(3);
          var cell5 = row.insertCell(4);
          var cell6 = row.insertCell(5);
          var cell7 = row.insertCell(6);
          
          row.setAttribute('id','row-bul')
          cell2.setAttribute('id','ad');
          cell3.setAttribute('id','soyad');
          cell4.setAttribute('id','bplace');
          cell5.setAttribute('id','bdate');

          cell1.innerHTML = "<input type='number' style='width:100%' name='IDara'>";
          cell2.innerHTML = "<input type='text' style='width:100%' name='NAMEara'>";
          cell3.innerHTML = "<input type='text' style='width:100%' name='LNAMEara'>";
          cell4.innerHTML = "<input type='text' style='width:100%' name='PLACEara'>";
          cell5.innerHTML = "<select style='width:100%' id='DATEara' name='DATEara'></selecet>";
          cell6.innerHTML = "<input class='btn btn-default' onclick='bul()' id='bul' type='submit' name='bul' value='BUL'>";
          cell7.innerHTML = "";
         
          var xmlhttp = new XMLHttpRequest();
          xmlhttp.open("GET", "ara.php?durum=date", true);
          xmlhttp.onload = function() {
            var data = this.responseText ;
            if(data) {
              $('#DATEara').html(this.responseText);
              //document.getElementById('DATEare').innerHTML = this.responseText;
            }
          };
          xmlhttp.send();
          if(document.getElementById("row-yeni")){document.getElementById("row-yeni").remove();}
          // $.ajax({
          //   type   : "GET",
          //   url    : "ara.php",
          //   data   : {'durum':'date'},
          //   success: function(result){
          //     $('#DATEara').html(result);
          //   }
          // });
        } 
        else{
          var row = document.getElementById('row-bul');
          row.remove()
        }
    }

    function bul(){
      var saklaButton = document.getElementById('bul').parentNode;
      var id = document.getElementsByName('IDara')[0].value;
      var name = document.getElementsByName('NAMEara')[0].value;
      var lname = document.getElementsByName('LNAMEara')[0].value;
      var bplace = document.getElementsByName('PLACEara')[0].value;
      var bdate = document.getElementsByName('DATEara')[0].value;
      var durum = 'bul';
      // var id = $("input[name=IDara]").val();
      // var name = $("input[name=NAMEara]").val();
      // var lname = $("input[name=LNAMEara]").val();
      // var bplace = $("input[name=PLACEara]").val();
      // var bdate = $("select[name=DATEara]").val();

      var xmlhttp = new XMLHttpRequest();
      xmlhttp.open("GET", "ara.php?durum="+durum+"&sid="+id+"&fname="+name+"&lname="+lname+"&birthplace="+bplace+"&birthdate="+bdate, true);
      xmlhttp.onload = function() {
        var data = this.responseText;
        if(data) {
          document.getElementById('tbody').innerHTML = data;
        }
        else{
          alert('Veri başarıyla bulunamadı.');
        }
      };
      xmlhttp.send();

        // $.ajax({
        //   type   : "GET",
        //   url    : "ara.php",
        //   data   : {"sid":id,
        //             "fname":name,
        //             "lname":lname,
        //             "birthplace":bplace,
        //             "birthdate":bdate,
        //             "durum":durum},
        //   success: function(result){
        //     $('#tbody').html(result);
        //   },
        //   error :function(result){
        //     alert('Veri başarıyla bulunamadı.');
        //   }
        // });
    }

    function guncelle(id){ 
      //$("#myTable").on('click','#guncelle',function(){
        var button = document.getElementById(id+'guncelle').children[0];
        var name = button.dataset.fname;//$(this).data('fname');
        var lname = button.dataset.lname;//$(this).data('lname');
        var bplace = button.dataset.bplace;//$(this).data('bplace');
        var bdate = button.dataset.bdate;//$(this).data('bdate');
  
        document.getElementById(name+id).innerHTML = "<input type='text' style='width:100%' name='g-name' value="+name+">";
        document.getElementById(lname+id).innerHTML = "<input type='text' style='width:100%' name='g-lname' value="+lname+">";
        document.getElementById(bplace+id).innerHTML = "<input type='text' style='width:100%' name='g-bplace' value="+bplace+">";
        document.getElementById(bdate+id).innerHTML = "<input type='date' style='width:100%' name='g-bdate' value="+bdate+">";
        document.getElementById(id+'guncelle').innerHTML = "<input onclick=sakla("+id+",\'"+name+"\'"+",\'"+lname+"\'"+",\'"+bplace+"\'"+",\'"+bdate+"\') id='sakla' type='submit' name='sakla' value='SAKLA'>";
      //});
    }

    function sakla(id,name,last,place,date){
      var saklaButton = document.getElementById('sakla').parentNode;
      var fname = document.getElementsByName('g-name')[0].value;
      var lname = document.getElementsByName('g-lname')[0].value;
      var bplace = document.getElementsByName('g-bplace')[0].value;
      var bdate = document.getElementsByName('g-bdate')[0].value;
      
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.open("GET","güncelle.php?&sid="+id+"&fname="+name+"&lname="+lname+"&birthplace="+bplace+"&birthdate="+bdate, true);
      xmlhttp.onload = function() {
        var data = this.responseText;
        if(data) {
          document.getElementById(name+id).id = fname+id;
          document.getElementById(last+id).id = lname+id;
          document.getElementById(place+id).id = bplace+id;
          document.getElementById(date+id).id = bdate+id;

          document.getElementById(fname+id).innerHTML = fname;
          document.getElementById(lname+id).innerHTML = lname;
          document.getElementById(bplace+id).innerHTML = bplace;
          document.getElementById(bdate+id).innerHTML = bdate;
          document.getElementById(id+'guncelle').innerHTML = "<input onclick='guncelle()' id='guncelle' data-id="+id+
                                                             " data-fname="+fname+" data-lname="+lname+" data-bplace="+bplace+
                                                             " data-bdate="+bdate+" type='submit' name='guncelle' value='GÜNCELLE'>";
          alert("Veri başarıyla güncellendi.");
        }
        else{
          alert("Veri başarıyla güncellenemedi.");
        }
      };
      xmlhttp.send();
      // $.ajax({
      //   method: "GET",
      //   url   : "güncelle.php",
      //   data  : {"sid":id,
      //            "fname":fname,
      //            "lname":lname,
      //            "birthplace":bplace,
      //            "birthdate":bdate},
      //   success: function(data){
      //     document.getElementById(name+id).id = fname+id;
      //     document.getElementById(last+id).id = lname+id;
      //     document.getElementById(place+id).id = bplace+id;
      //     document.getElementById(date+id).id = bdate+id;

      //     document.getElementById(fname+id).innerHTML = fname;
      //     document.getElementById(lname+id).innerHTML = lname;
      //     document.getElementById(bplace+id).innerHTML = bplace;
      //     document.getElementById(bdate+id).innerHTML = bdate;
      //     document.getElementById(id+'guncelle').innerHTML = "<input onclick='guncelle()' id='guncelle' data-id="+id+
      //                                                        " data-fname="+fname+" data-lname="+lname+" data-bplace="+bplace+
      //                                                        " data-bdate="+bdate+" type='submit' name='guncelle' value='GÜNCELLE'>";
      //     alert("Veri başarıyla güncellendi.");
      //   },
      //   error : function(data){
      //     alert("Veri başarıyla güncellenemedi.");
      //   }
      // });
    }

    function sil(id){
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.open("GET", "sil.php?sid=" + id, true);
      xmlhttp.onload = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById(id).remove();
          alert("Veri başarıyla silindi.");
        }
        else if(this.status == 403){
          alert("Veri başarıyla silinemedi.");
        }
      };
      xmlhttp.send();
      
    /*$.ajax({
        type   : "GET",
        url    : "sil.php",
        data   : {"sid":id},
        success: function(data){
          $("#"+id).remove();
          alert("Veri başarıyla silindi.");
        },
        error  : function(data){
          alert("Veri başarıyla silinemedi.");
        }
      });*/
    }
  </script>
<?php
}

function pageBottom(){
  echo "</body>
        </html>";
}
?>