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
    <script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/sha1.js"></script>    <script type="text/javascript">
    var user = '<?php echo sha1($username);?>';
    var peer = new Peer(user, {key: '5jrhcy14ieezh0k9', debug: 1});
    var meetings = undefined;
    var version  = undefined;
    //Now initialise available users
    var peers = JSON.parse('<?php  echo $peers; ?>');
    // var list = '<?php if (isset($list)) { echo $list;} ?>';
    //End of variables
    //Find the latest version of the list
    var ver = $.ajax({
    url: "/api/meetings/get/latest",
    context: document.body
    }).done(function() {
      version = ver.responseText;
      console.log('Got the latest version of the list '+ver.responseText);
    });
    //Pick a random person from the array and try to get the list from them
    var rand = peers[Math.floor(Math.random() * peers.length)];
    //Check wether we have any users.
    if (typeof(rand)=="undefined") 
      {
        //We have no users present - query the server.
        console.log('No peers present. Querying the server for data.');
        var request = $.ajax({
        url: "/api/meetings/get/list",
        context: document.body
        }).done(function() {
          meetings = JSON.parse(request.responseText);
          console.log(meetings[meetings.length-1]['hash']);
        });
      } else {
        console.log('Peers available');
        var conn = peer.connect(CryptoJS.SHA1(rand).toString());
        conn.on('open', function()
        {
          console.log('Sent a request for the meeting list!');
          conn.send({'get':version});
        });
        conn.on('data', function(data){
          // Will print 'hi!'
          console.log('Got a response from peer. Meetings are now available');
          console.log(data);
          meetings = data;
        });
      }

    //Now query the user for the latest version of the list
    peer.on('connection', function(conn) {
      conn.on('data', function(data){
        // Will print 'hi!'
        console.log('Got a connection');
        console.log(data);
        console.log(data['get']);
        if (data['get'] == version) 
        {
          console.log('Peer has requested the current meeting list');
          conn.send(meetings);
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