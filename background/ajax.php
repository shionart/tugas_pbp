<?php
    $link = mysqli_connect('localhost', 'root', '');
    mysqli_select_db($link, 'db_toko');

    $province = $_GET["province"];

    if($province!="") {
    $res = mysqli_query ($link, "select * from kabupaten where id_provinsi=$province ORDER BY nama");
    echo "<select class='styled-select blue semi-square' name='city'>";
    while($row = mysqli_fetch_array($res)) {
    echo "<option value='".$row['id_kabupaten']."'>"; echo $row['nama']; echo "</option>";
    }
    echo "</select>";
    }
?>
