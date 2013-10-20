<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $title ?></title>
    <script type="text/javascript" src="<?php echo asset_url('peer.js','js');?>"></script>
    <script type="text/javascript">
    var peer = new Peer('bozhkov.atanas@gmail.com', {key: '5jrhcy14ieezh0k9'});
    var conn = peer.connect('second');
    conn.on('open', function(){
        conn.send('WOW!');
    });
    </script>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo asset_url('bootstrap.css','css');?>" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo asset_url('signin.css','css');?>" rel="stylesheet">
    <script src="<?php echo asset_url('jqueryf.js','js');?>"></script>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../../assets/js/html5shiv.js"></script>
      <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>