<?php 
include($_SERVER['DOCUMENT_ROOT'].'/config/metadata.php');
include($_SERVER['DOCUMENT_ROOT'].'/config/core.php');
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compability" content="IE=edge">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css">
  <meta name="description" content="<?php echo($description); ?>">
    <meta name="author" content="<?php echo($author); ?>">
    <title><?php echo($title); ?></title>
    <!-- Bootstrap css -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <!-- for material design, if we use it, otherwise, just delete this -->
    <!--
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
    -->
  <!-- Pake kustom theme -->
  <link rel="stylesheet" href="<?php echo $home_url."/vendor/css/theme.css" ?>" type="text/css">
  <!-- Script: Animated entrance -->
  <script src="<?php echo $home_url."/vendor/js/animate-in.js"?>"></script>
  <script src="<?php echo $home_url."/vendor/js/anime.min.js"?>"></script>
  <?php include('script.php'); ?>
</head>