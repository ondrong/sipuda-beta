<?php
include(ROOT_PATH.'/config/dbconnection.php');
if(isset($_SESSION['masuk'])!='')
{
    $_SESSION['masuk']='';
}
if(isset($_POST['masuk']))
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Build POST request:
        $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptcha_secret = '6Lf5uIMUAAAAAOw5uFGDdFYKpaT9Ut-AUMZotIjN';
        $recaptcha_response = $_POST['recaptcha_response'];
    
        // Make and decode POST request:
        $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
        $recaptcha = json_decode($recaptcha);
        
        if (isset($recaptcha->score) >= 0.5)
        {
            $email = $_POST['surel'];
            $password = $_POST['katasandi'];
            $sql = "SELECT surel,katasandi,IDAnggota,status,level_akses FROM anggota WHERE surel=:surel and katasandi=PASSWORD(:katasandi)";
            $query = $dbs -> prepare($sql);
            $query-> bindParam(':surel', $email, PDO::PARAM_STR);
            $query-> bindParam(':katasandi', $password, PDO::PARAM_STR);
            $query-> execute();
            $results = $query->fetchAll(PDO::FETCH_OBJ);

            if($query->rowCount() > 0)
            {
                foreach ($results as $result) 
                {
                    $_SESSION['IdAnggota'] = $result->IDAnggota && $_SESSION['Level_Akses'] = $result->level_akses;
                    if($result->status==1 && $result->level_akses=='Anggota')
                    {
                        $_SESSION['masuk'] = $_POST['surel'];
                        echo "<script type='text/javascript'> document.location ='/'; </script>";
                    }
                    else if($result->status==1 && $result->level_akses=='Admin')
                    {
                        $_SESSION['masuk'] = $_POST['surel'];
                        echo "<script type='text/javascript'> document.location = 'admin/index.php'; </script>";
                    }
                    else if($result->status==0)
                    {
                        $error_msg = "<div class='login-modal'>Mohon maaf, akun Anda masih dalam peninjauan. Mohon coba kembali nanti.</div>";
                        $script =  "<script> $(document).ready(function(){ $('#login-modal').modal('show'); }); </script>";
                    }
                    else
                    {
                        $error_msg = "<div class='login-modal'>Mohon maaf, akun Anda diblokir. Silahkan hubungi Pustakawan atau Pusat Informasi di Perpustakaan.</div>";
                        $script =  "<script> $(document).ready(function(){ $('#login-modal').modal('show'); }); </script>";
                    }
                }

            } 
            else
            {
                $error_msg = "<div class='login-modal'>Surel atau Kata Sandi Anda salah. Silahkan coba lagi.</div>";
                $script =  "<script> $(document).ready(function(){ $('#login-modal').modal('show'); }); </script>";
            }
        }
        else {
            $error_msg = "<div class='login-modal'>Verifikasi Gagal. Silahkan coba kembali</div>";
            $script =  "<script> $(document).ready(function(){ $('#login-modal').modal('show'); }); </script>";
        }
    }
}
?>