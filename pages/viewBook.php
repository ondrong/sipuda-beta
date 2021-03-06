<?php
require($_SERVER['DOCUMENT_ROOT'].'/config/dbconnection.php');
require($_SERVER['DOCUMENT_ROOT'].'/config/core.php');

// buku
$lihatbuku = $dbs->prepare('SELECT * FROM buku WHERE nomor_panggil=:nomor_panggil');
$lihatbuku->execute(array(':nomor_panggil' => $_GET['nopang']));
$baris = $lihatbuku->fetch();

$cekbukupinjam = $dbs->prepare('SELECT * FROM peminjaman WHERE nomor_panggil_buku=:nomor_panggil');
$cekbukupinjam->execute(array(':nomor_panggil' => $_GET['nopang']));
$baris2 = $cekbukupinjam->fetch();

//Jika buku tidak ada, alihkan pengguna ke beranda
if($baris['nomor_panggil'] == '')
{
    header('Location: ./');
    exit();
}

$page_title = $baris['judul_buku'];
include('header.php');
include('navbar.php');
?>
<body>
  <div class="py-5 text-center section-parallax" style="background-image: url(&quot;<?php echo $baris['gambar'] ?>&quot;);">
    <div class="container-fluid">
      <div class="row my-3 mx-auto col-md-6 p-3 section-lighttwo">
        <div class="mx-auto col-md-10 p-3">
        <?php  
          echo "<h1>".$baris['judul_buku']."</h1>";
          echo "<p class='mb-12'>".$baris['deskripsi_singkat']."</p>";
        ?>
        </div>
        <?php
        include($_SERVER['DOCUMENT_ROOT'].'/models/searchbar.php');
        ?>
      </div>
    </div>
  </div>
  <div class="py-3 px-3">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <ul class="breadcrumb d-flex flex-row align-items-start w-100">
            <!-- Kode untuk untuk menampilkan breadcrumb -->
            <?php 
             include('breadcrumb.php');
            ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="py-3 px-3">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-10 mx-auto">
          <div class="card">
            <div class="row no-gutter">
              <div class="col-auto">
                  <?php
                    // impor fungsi timeago()
                    include($_SERVER['DOCUMENT_ROOT'].'/config/timeago-function.php');
                  
                    // the CardView, tolong rapiin ya ;)
                    echo"<img alt='Card image cap' class='img-fluid' src=".$baris['gambar']." style='	height: 405px;'>";
              echo "</div>";
              echo "<div class='col-4'>";
                echo "<div class='card-block px-2 flex-column'>";
                    echo "<h3 class='card-title'>".$baris['judul_buku']."</h3>";
                    echo "<p class='card-text'>".$baris['pengarang']."</p>";

                    // ambil tahunnya saja
                    // contoh dari 2018-05-02 menjadi 2018 saja
                    $tahun = date("Y", strtotime($baris['tahun_terbit']));
                    echo "<p class='card-text'>".$tahun."</p>";
                    echo "<br class='card-text' />";

                    //mengconvert dari angka ke string
                    if($baris['status_buku'] == '0')
                    {
                      $status_buku = 'Sedang Dipinjam';
                    }
                    else if($baris['status_buku'] == '1')
                    {
                      $status_buku = 'Tersedia';
                    }
                    else if($baris['status_buku'] == '2')
                    {
                      $status_buku = 'Hilang';
                    }
                    else
                    {
                      $status_buku = 'Tidak diketahui keadaannya';
                    }

                    echo "<h4 class='card-text'>".$status_buku."</h4>";
                    echo "<br class='card-text' />";
                    echo "<p class='card-text'>Terdapat ".$baris['lbr_halaman']." halaman dibuku ini.</p>";
                    echo "<p class='card-text'><i>".$baris['kategori']."</i></p>";
                    
                    if(isset($_SESSION['masuk']) && $_SESSION['masuk']==true && $_SESSION['Level_Akses']=='Anggota' && $baris['status_buku']=='1' && !isset($baris2['is_accepted']))
                    {
                      
                      echo "<button data-toggle='modal' data-target='#pinjamModal' data-id='".$baris['nomor_panggil']."' id='pinjam' class='btn btn-outline-primary btn-lg'> Pinjam</button>";
                      
                      include_once("pinjamModal.php");
                      //echo "</div>";
                      //echo "</div>";
                    }
                    else if($_SESSION['Level_Akses']=='Admin' || $_SESSION['Level_Akses']=='Pustakawan')
                    {
                      // untuk cek jenis bahan apa saja yang ada
                      $sqlkat = $dbs->prepare('SELECT * FROM jenis_bahan');
                      $sqlkat->execute();
                      $hasilkat = $sqlkat->fetchAll(PDO::FETCH_OBJ);

                      echo "<a href='' class='btn btn-outline-primary' data-toggle='modal' data-target='#suntingbuku".$baris['nomor_panggil']."'><i class='fas fa-edit'></i> Sunting</a>";
                      echo "<a href='' class='btn btn-outline-danger' data-toggle='modal' data-target='#hapusbuku".$baris['nomor_panggil']."'><i class='fas fa-trash '></i> Hapus</a>";
                      include_once(ROOT_PATH."/account/admin/buku/suntingbuku.php");
                      include_once(ROOT_PATH."/account/admin/buku/hapusbuku.php");
                    }
                    else
                    {
                      // Nothing happened lel
                    }
                  //echo "</div>";
                echo "</div>";
              echo "</div>";
              echo "<div class='col-4'>";
                echo "<div class='card-block px-2 flex-column'>";
                  echo "<p class='card-text'><strong>Nomor Panggil</strong><br />".$baris['nomor_panggil']."</p>";
                  echo "<p class='card-text'><strong>Nomor ISBN</strong><br />".$baris['isbn']."</p>";
                  echo "<p class='card-text'><strong>Penerbit</strong><br />".$baris['penerbit']."</p>";
                  echo "<p class='card-text'><strong>Jenis Bahan</strong><br />".$baris['jenis_bahan']."</p>";
                    echo "<br class='card-text' />";
                        
                  if(!$baris['konten_digital'] == '' || !$baris['konten_digital'] == 'NULL')
                  {
                    echo "<p class='card-text'>";
                    echo "<strong>Konten Digital</strong>";
                    echo "<br />Tidak Ada Konten Digital</p>";
                  }
                  else
                  {
                    echo "<p class='card-text'>";
                    echo "<strong>Konten Digital</strong>";
                    echo "<br />Ada</p>";
                  }
                echo "</div>";
                //echo "</div>";
              echo "</div>";
            echo "</div>";
            echo "<div class='card-footer w-100 text-muted'>";
              echo "<p class='text-left'>";
              echo "Dimodifikasi ";
              echo timeago($baris['tgl_perubahan']);
              echo "<span class='float-right'>".$baris['numofcopies']." Buku Yang Sama Lainnya Tersedia.</span>";
              echo "</p>";
            echo "</div>";
          echo "</div>";
        echo "<br />";
                  ?>                
        </div>
      </div>
      <?php include_once(ROOT_PATH."/models/bukusalinan.php");
      ?>
    </div>
  </div>


<?php
include('script/viewBookScript.php');
include('footer.php');

/* tutup koneksi */
mysqli_close($con)
?>
