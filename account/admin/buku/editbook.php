<?php
require($_SERVER['DOCUMENT_ROOT'].'/config/dbconnection.php');
require($_SERVER['DOCUMENT_ROOT'].'/config/core.php');


    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        
        $bids=$_POST['nopangs'];
        $juduls=$_POST['juduls'];
        $deskripsis=$_POST['deskripsis'];
        $pengarangs=$_POST['authors'];
        $tahunterbits=$_POST['tahunterbits'];
        $ketersediaans=$_POST['ketersediaans'];
        $isbns=$_POST['isbns'];
        $penerbits=$_POST['penerbits'];
        $gambars=$_POST['gambars'];
        $salinans=$_POST['salinans'];
        $hals=$_POST['hals'];
        $categorys=$_POST['categorys'];
        $jenisbahans=$_POST['jenisbahans'];

        $sql="UPDATE buku SET judul_buku=:judul, deskripsi_singkat=:deskripsiskt, pengarang=:pengarang, tahun_terbit=:tahunterbit, status_buku=:ketersediaan, isbn=:isbn, penerbit=:penerbit, tgl_perubahan=NOW(), gambar=:gambar, numofcopies=:salinan, lbr_halaman=:hal, kategori=:category, jenis_bahan=:jenisbahan WHERE buku.nomor_panggil=:bid";
        
        $query = $dbs->prepare($sql);
        $query->bindParam(':bid',$bids,PDO::PARAM_STR);
        $query->bindParam(':judul',$juduls,PDO::PARAM_STR);
        $query->bindParam(':deskripsiskt',$deskripsis,PDO::PARAM_STR);
        $query->bindParam(':pengarang',$pengarangs,PDO::PARAM_STR);
        $query->bindParam(':tahunterbit',$tahunterbits,PDO::PARAM_STR);
        $query->bindParam(':ketersediaan',$ketersediaans,PDO::PARAM_STR);
        $query->bindParam(':isbn',$isbns,PDO::PARAM_STR);
        $query->bindParam(':penerbit',$penerbits,PDO::PARAM_STR);
        $query->bindParam(':gambar',$gambars,PDO::PARAM_STR);
        $query->bindParam(':salinan',$salinans,PDO::PARAM_STR);
        $query->bindParam(':hal',$hals,PDO::PARAM_STR);
        $query->bindParam(':category',$categorys,PDO::PARAM_STR);
        $query->bindParam(':jenisbahan',$jenisbahans,PDO::PARAM_STR);
        $query->execute();
        $idpinjams = $dbs->lastInsertId();

        if(isset($idpinjams))
        {
            $_SESSION['message']="Buku telah disunting";
            //echo "<script>window.confirm('Sukses. Silahkan refresh halaman untuk update tabel.')</script>";
            echo "<li class='fas fa-check-circle fa-3x'></li><h1 class='text-center'>Sukses! Buku dengan nomor panggil ".$bids.". telah diperbaharui </h1>";
            //header('location:/account/admin');
        }
        else
        {
            $_SESSION['error']="Ada yang tidak beres. Mohon coba lagi.";
            //echo "<script>window.confirm('Galat, salah di sql nya kayanya')</script>";
            echo "<h1 class='text-center'>Ada yang aneh".$idpinjams."</h1>";
            //header("location:" . $_SERVER['HTTP_REFERER']);
        }
    }
    else
    {
    echo "<script>window.confirm('Galat, lain post')</script>";
    }
?>