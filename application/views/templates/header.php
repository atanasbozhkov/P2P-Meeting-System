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
    <script type="text/javascript" src="<?php echo asset_url('dht.js','js');?>"></script>
    <script type="text/javascript" src="<?php echo asset_url('jstorage.js','js');?>"></script>
    <script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/sha1.js"></script>
    <script type="text/javascript" src="<?php echo asset_url('json2.js','js');?>"></script>
    <script type="text/javascript" src="<?php echo asset_url('json-to-table.js','js');?>"></script>
    <script> /* $.jStorage is now available */ </script>
    <script type="text/javascript">

    var user = '<?php echo sha1($username);?>';
    var email = '<?php echo ($username);?>';
    // var peer = new Peer(user, {key: '5jrhcy14ieezh0k9', debug: 3});
    var peer = new Peer(user, {host: 'localhost', port: 9000, debug: 1});
    var meetings = undefined;
    var version  = undefined;
    var peerData = undefined;
    var peers = JSON.parse('<?php  echo $peers; ?>');
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
    // var rand = peers[Math.floor(Math.random() * peers.length)];

    //Get all hashes from the database. Potentially dangerous
    var hashes = $.ajax({
    url: "/api/meetings/get/all",
    context: document.body
    }).done(function() {
      hashesList = ver.responseText;
      console.log('All hashes are:'+hashes.responseText);
    });


    //TODO: This has to be reworked
    //Check wether we have any users.
    if (typeof(rand)=="undefined")
      {
        //We have no users present - query the server.
        // console.log('No peers present. Querying the server for data.');
        var request = $.ajax({
        url: "/api/meetings/get/user/"+encodeURIComponent(email),
        context: document.body
        }).done(function() {
          meetings = JSON.parse(request.responseText);
          console.log(meetings);
          //Put the table
           var jsonHtmlTable = ConvertJsonToTable(meetings, 'meetingsTable', null, 'Download');
           document.getElementById('meetings').innerHTML = '<center>'+jsonHtmlTable+'</center><br/><br/>';
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
          //[todo] - Add a case for when a user does not have the current version.
          console.log('Got a response from peer. Meetings are now available');
          console.log(data);
          meetings = data;
        });
      }

    //Now query the user for the latest version of the list

    peer.on('connection', function(conn) {
      conn.on('data', function(data){
        //Connection from peer.
        // Add check for old version.
        console.log('Got a connection');
        console.log(data);
        if (data['rpc'] == 'request')
        {
          console.log('Peer has requested his details - downloading');
          //1.Download meetings
          console.log('URL:'+"/api/meetings/get/user/"+encodeURIComponent(data['username']));
        var request = $.ajax({
        url: "/api/meetings/get/user/"+encodeURIComponent(data['username']),
        context: document.body
        }).done(function() {
          console.log('respone is:'+request.responseText);
          // peerData = request.responseText;
          //2.Save them as a cookie
          $.jStorage.set(data['username'], request.responseText);
          console.log('have we data?:'+request.responseText);
          conn.send(request.responseText);
          console.log('Meetings sent');
        });

          //3.Send them to the user.
        } else if (data['rpc'] == "saveNewData") {
          //Someone has created a new meeting.
          //Store it.
          console.log('wat');
        } else {
          console.log('Peer has requested a different meeting list. Perhaps newer?');
          // [review] - Protocol for different versions.
          conn.send(0)
        }
      });
    });
    </script>
    <script type="text/javascript">

    //Debug
    var myNodeID = hexToBytes(CryptoJS.SHA1(peer.id).toString());
    console.log('My nodeID is:'+ myNodeID)
    //Create a routing Table for DHT
    //and fill it with the current users.
    var rt = NewRoutingTable(myNodeID);
    for (var i = 0; i < peers.length; i++) {
      Update(peers[i],rt);
    };

    var closestToSelf = FindClosest(myNodeID,10,rt);
    console.log('10 Closest to self are:'+ closestToSelf.length);

    for (var i = 0; i < closestToSelf.length; i++) {

      var contactee = closestToSelf[i].node;
      var conn = peer.connect(CryptoJS.SHA1(contactee).toString());
      console.log(CryptoJS.SHA1(contactee).toString())
      conn.on('open', function(){
        //RPC Request for own details.
        conn.send({rpc:'request', username:email});
        console.log('sent RPC to '+ contactee);
      });
      conn.on('data', function(data){
        //Finally - return the user's data
        console.log(JSON.parse(data));
        var data = JSON.parse(data);
        if (data.length == 1) {
          console.log('one');
          //Convert the date to a usable string
          var temp = new Date(data[0].date);
          data[0].date = temp.toString();
         var jsonHtmlTable = ConvertJsonToTable([data[0]], 'meetingsTable', null, 'Download');
       }

        else {
          //Convert the date to a usable string
          for (var i = 0; i < data.length; i++) {
            var temp = new Date(data[i].date);
            data[i].date = temp.toString();
          };
          var jsonHtmlTable = ConvertJsonToTable(data, 'meetingsTable', null, 'Download');
          console.log('many');
        }
        console.log(jsonHtmlTable);
        document.getElementById('meetings').innerHTML = '<center>'+jsonHtmlTable+'</center><br/><br/>';
        //Function for capitalising each first letter
        function toTitleCase(str)
        {
          return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
        }
        //Now take each table header, capitalise it and center it.
        $('th').each(function() {
         var temp = $( this ).html();
        $(this).html(toTitleCase(temp));
        $(this).css('text-align','center');
        });
        //For every user in the invetees list: create a vector and send a {rpc:save} to the peers
        //Fill the data on the page
      });
    };
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
    echo "[DEBUG] Online peers:";
    //Dat check..
    if ($peers == "[]") {
      echo "None";
    } else{
    print_r($peers);
    }
  }

  ?>