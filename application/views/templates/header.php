<!DOCTYPE html>
<html lang = "en" >
<head>
  <meta charset = "utf-8" >
  <meta name = "viewport" content = "width=device-width, initial-scale=1.0" >
  <meta name = "description" content = "" >
  <meta name = "author" content = "" >
  <title><?php echo $title ?></title >
  <script src = "<?php echo asset_url('jquery.js','js');?>" ></script>
  <script type = "text/javascript" src = "<?php echo asset_url('peer.js','js');?>" ></script>
  <script type="text/javascript" src="<?php echo asset_url('dht.js', 'js'); ?> "></script>
  <script type="text/javascript " src="<?php echo asset_url('jstorage.js', 'js'); ?> "></script>
  <script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/sha1.js"></script>
  <script type = "text/javascript" src = "<?php echo asset_url('json2.js','js');?>" ></script>
  <script type="text/javascript" src="<?php echo asset_url('json-to-table.js', 'js'); ?> "></script>
  <link href='<?php echo asset_url('fullcalendar.css','css');?>' rel='stylesheet' />
  <link href='<?php echo asset_url('fullcalendar.print.css','css');?>' rel='stylesheet' media='print'/>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
  <script src='<?php echo asset_url('fullcalendar.min.js','js');?>'></script>
  <script type="text/javascript ">
    $(function() {
        $( document ).tooltip();
      });
    var user = '<?php echo sha1($username);?>';
    var email = '<?php echo ($username);?>';
    // var peer = new Peer(user, {key: '5jrhcy14ieezh0k9', debug: 3});
    var peer = new Peer(user, {
        host: 'localhost',
        port: 9000,
        debug: 1
    });
    var meetings = undefined;
    var version = undefined;
    var peerData = undefined;
    var peers = JSON.parse('<?php  echo $peers; ?>');
    //End of variables

    //Pick a random person from the array and try to get the list from them
    var rand = peers[Math.floor(Math.random() * peers.length)];

    //Functions
    function setMeetingStatus(meetingid,status){
	console.log("Set meeting status invoked.");
	var request = $.ajax({
            url: "/api/meetings/setseen/" + meetingid + '/' + status,
            context: document.body
        }).done(function() {
		console.log('Request is finished');
		location.reload();
	})}


    function putNotifications(meets){
    	for (var i = 0; i < meets.length; i++) {
    		if(meets[i].status == '0'){
    			$('#notifications').append('<div style="float:left;">'+meets[i].name+'</div><div style="float:right;"><a href="#" onClick="setMeetingStatus('+meets[i].id+',\'1\')"> Accept </a> &nbsp; <a href="#" onClick="setMeetingStatus('+meets[i].id+',\'2\')"> Decline </a> </div><br/> ');
    		}
    	};
    }

    function putCalendar (meetings) {
        var date = new Date();
       var d = date.getDate();
       var m = date.getMonth();
       var y = date.getFullYear();

       $('#calendar').fullCalendar({
         header: {
           left: 'prev,next today',
           center: 'title',
           right: 'month,basicWeek,basicDay'
         },
         editable: false,
         events: meetings,
         eventClick: function(calEvent, jsEvent, view) {
             $("span:contains("+calEvent.title+")").tooltip({ content: calEvent.notes+'<br/>'+calEvent.invitees, "items":"span" })
             $(this).css('border-color', 'red');

         }
       });
    }
    function sleep(milliseconds) {
      var start = new Date().getTime();
      for (var i = 0; i < 1e7; i++) {
        if ((new Date().getTime() - start) > milliseconds){
          break;
        }
      }
    }
    //Check wether we have any users.
    if (typeof(rand) == "undefined") {
        //We have no users present - query the server.
        console.log('No peers present. Querying the server for data.');
        var request = $.ajax({
            url: "/api/meetings/get/user/" + encodeURIComponent(email),
            context: document.body
        }).done(function() {
            meetings = JSON.parse(request.responseText);
            console.log(meetings);
	    //Scan the meetings for notifications and put them
	    putNotifications(meetings);
            //Transform the array to the calendar plugin form
            var calMeetings = [];
            for (var i = 0; i < meetings.length; i++) {
		//Only put meetings in the calendar which have been accepted.
		if(meetings[i].status == 1){
                calMeetings.push({
		    id: meetings[i].id,
                    title: meetings[i].name,
                    start: meetings[i].date,
                    notes: meetings[i].notes,
                    invitees: meetings[i].invitees,
                    status: meetings[i].status
		})

	    }
	};
            console.log('Got the meetings, now putting them on the calendar');
            //Put the calendar
           putCalendar(calMeetings);

        });
    }

    //Now query the user for the latest version of the list

    peer.on('connection', function(conn) {
        conn.on('data', function(data) {
            //Connection from peer.
            console.log('Got a connection');
            console.log(data);
            if (data['rpc'] == 'request') {
                console.log('Peer has requested his details - downloading');

                //1.Download meetings
                console.log('URL:' + "/api/meetings/get/user/" + encodeURIComponent(data['username']));
                var request = $.ajax({
                    url: "/api/meetings/get/user/" + encodeURIComponent(data['username']),
                    context: document.body
                }).done(function() {
                    console.log('respone is:' + request.responseText);
                    // peerData = request.responseText;

                    //2.Save them as a cookie
                    $.jStorage.set(data['username'], request.responseText);
                    console.log('have we data?:' + request.responseText);
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
    <script type="text/javascript ">
//Debug
var myNodeID = hexToBytes(peer.id);
console.log('My nodeID is:' + myNodeID)
//Create a routing Table for DHT
//and fill it with the current users.
var rt = NewRoutingTable(myNodeID);
for (var i = 0; i < peers.length; i++) {
    Update(peers[i], rt);
};

var closestToSelf = FindClosest(myNodeID, 10, rt);
console.log('10 Closest to self are:' + closestToSelf.length);

for (var i = 0; i < closestToSelf.length; i++) {

    var contactee = closestToSelf[i].node;
    var conn = peer.connect(CryptoJS.SHA1(contactee).toString());
    console.log(CryptoJS.SHA1(contactee).toString())
    conn.on('open', function() {
        //RPC Request for own details.
        conn.send({
            rpc: 'request',
            username: email
        });
        console.log('sent RPC to ' + contactee);
    });
    conn.on('data', function(data) {
        //Finally - return the user's data
        console.log(JSON.parse(data));
        var data = JSON.parse(data);
        if (data.length == 1) {
            console.log('one');
            //Convert the date to a usable string
            var temp = new Date(data[0].date);
            data[0].date = temp.toString();
            //Fit the idata into the calendar function
            var calMeetings = [];
	    //Scan for notifications
	    putNotifications(data);
            for (var i = 0; i < data.length; i++) {
		if(data[i].status == 1){
			calMeetings.push({
			    title: data[i].name,
			    start: data[i].date,
			    notes: data[i].notes,
			    invitees: data[i].invitees,
			    status: data[i].status
			})

	    }
	};
            putCalendar(calMeetings);

        } else {
            //Convert the date to a usable string
            for (var i = 0; i < data.length; i++) {
                var temp = new Date(data[i].date);
                data[i].date = temp.toString();
            };
            // var jsonHtmlTable = ConvertJsonToTable(data, 'meetingsTable', null, 'Download');
            var calMeetings = []
            var myInvitees = [];
            for (var i = 0; i < data.length; i++) {
                calMeetings.push({
                    title: data[i].name,
                    start: data[i].date,
                    notes: data[i].notes,
                    invitees: data[i].invitees
                });
                // console.log(data[i].invitees.split(','));
                myInvitees = myInvitees.concat(data[i].invitees.split(','));
            };
            putCalendar(calMeetings);
            console.log('many');
            console.log('Invitees are ready');
            // console.log(data[0].name)
        }
        //For every user in the invetees list: create a vector and send a {rpc:save} to the peers
        //Fill the data on the page
        myInvitees = myInvitees.filter(function(elem, pos) {
            return myInvitees.indexOf(elem) == pos;});

        var len = myInvitees.length;
        for (var i = 0; i < len; i++) {
            var invNode = hexToBytes(CryptoJS.SHA1(myInvitees[i]).toString());
            var invRT = NewRoutingTable(invNode);
            for (var j = 0; j < peers.length; j++) {
                Update(peers[j], invRT);
            };
            var closestToInv = FindClosest(invNode, 10, invRT);
            //Now that we have the vector - send RPCs
            for(var k = 0; k < 10; k++){

                var contactee = closestToInv[k].node;
                var connInv = peer.connect(CryptoJS.SHA1(contactee).toString());
                connInv.on('open', function() {
                    //RPC Request to save details
                    connInv.send({
                        rpc: 'saveUserData',
                        data: 'dummy'
                    });
                    console.log('sent RPC to ' + contactee);
                });
                connInv.on('data', function(data) {
                    console.log('Recieved confirmation from peer;');
                });
            }
            //Cleanup
            if (closestToInv =! undefined) {
                delete(closestToInv);
                delete(invNode);
                delete(invRT);
            }

        };
    });
};
    </script>
    <!-- Bootstrap core CSS -->
    <link href=" <?php echo asset_url('bootstrap.css', 'css'); ?> " rel="
stylesheet ">

    <!-- Custom styles for this template -->
    <link href=" <?php echo asset_url('signin.css', 'css'); ?> " rel="
stylesheet ">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src=".. / .. / assets / js / html5shiv.js "></script>
      <script src=".. / .. / assets / js / respond.min.js "></script>
    <![endif]-->
  </head>

  <body>
  <br/><br/><br/>
  <?php
  if (isset($peers))
  {
    echo " [DEBUG] Online peers: ";
    //Dat check..
    if ($peers == "[]") {
      echo "None";
    } else{
    print_r($peers);
    }
  }

  ?>
