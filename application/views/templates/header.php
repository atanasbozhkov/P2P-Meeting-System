<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <script src="<?php echo asset_url('jquery.js','js');?>"></script>
    <title><?php echo $title ?></title>
    <script type="text/javascript" src="<?php echo asset_url('peer.js','js');?>"></script>
    <script type="text/javascript">
    var user = '<?php echo sha1($username);?>';
    var peer = new Peer(user, {key: '5jrhcy14ieezh0k9', debug: 1});
    //Now initialise available users
    var peers = JSON.parse('<?php  echo $peers; ?>');
    // var list = '<?php if (isset($list)) { echo $list;} ?>';

    //End of variables
    //Pick a random person from the array and try to get the list from them
    //TODO: What happens when no users are present?
    var rand = peers[Math.floor(Math.random() * peers.length)];
    //Now query the user for the latest version of the list
    // if (user == '') {};
    var version = '9aac';
    var hardlist = {
      name: 'test', date: 'test', location: 'test', invitees: 'test', notes: 'test'
    }
    peer.on('connection', function(conn) {
      conn.on('data', function(data){
        // Will print 'hi!'
        console.log('Got a connection');
        console.log(data);
        console.log(data['get']);
        if (data['get'] == version) 
        {
          console.log('Peer has requested the current meeting list');
          conn.send(hardlist);
          console.log('Meeting list sent');
        };
      });
    });
    </script>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo asset_url('bootstrap.css','css');?>" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo asset_url('signin.css','css');?>" rel="stylesheet">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../../assets/js/html5shiv.js"></script>
      <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
  <br/><br/><br/>
  <?php 
  if (isset($peers)) 
  {
    print_r($peers);
  } 

  ?>