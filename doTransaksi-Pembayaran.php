<?php

session_start();
include 'config/koneksi.php';

$nama       = $_POST['nama'];
$tgl        = date("d-m-Y");
$invoice    = $_POST['invoice'];
$kelas      = $_POST['kelas'];
$id_user    = $_POST['id_user'];
$total      = $_POST['total'];
$target_dir = "foto/";

$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["simpan"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        // echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}

// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif") {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        // echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
 

$queryGetInvoice = "SELECT * FROM pembelian WHERE invoice = '$invoice'";
$sqlQueryGetInvoice = mysqli_query($konek, $queryGetInvoice);
$dataInvoice = mysqli_fetch_array($sqlQueryGetInvoice);

$invoice2       = $dataInvoice['invoice'];
$total_harga    = $dataInvoice['total_harga'];

$sqlInsert  = "INSERT INTO transaksi(id_user,tanggal,nama_pembeli,total,upload,invoice,total_harga)
			   VALUES ('$id_user','$tgl','$nama','$total','$target_file','$invoice','$total_harga')";
$query = mysqli_query($konek,$sqlInsert);


if($query){
    echo "<strong><center>Bukti Pembayaran Berhasil Dikirim";
    echo '<META HTTP-EQUIV="REFRESH" CONTENT = "1; URL=index.php?halaman=index">'; 
}
?>