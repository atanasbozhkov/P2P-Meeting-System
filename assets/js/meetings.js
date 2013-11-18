$(document).ready(function() {
        //On document ready ask for latest meeting file
        //TODO Implement no peers case
        var request = $.ajax({
        url: "/api/meetings/get/latest",
        context: document.body
        }).done(function() {
            console.log('The lastest meeting list has hash of:'+request.responseText);
            list = request.responseText;
            //we have the latest list now querry the user
            if(user != '08988b2ba7d538e9f43a780023098e1571e5a6d9')
            {
              var conn = peer.connect('08988b2ba7d538e9f43a780023098e1571e5a6d9');
              // console.log('here');
              conn.on('open', function()
              {
                console.log('Sent a request for the meeting list!');
                conn.send({'get':list});
              });
              conn.on('data', function(data){
                // Will print 'hi!'
                console.log('Got a response');
                console.log(data);
              });
            }
            //end done
          });
        //End of AJAX Request
        // $(function() {
        //       $("#submitter").click( function()
        //            {
        //             //Gather all the values and post them via API to /api/meeting/new
        //             // alert('button clicked');
        //             var name = $('#name')[0].value.trim();
        //             var date = $('#date')[0].value.trim();
        //             var loc  = $('#location')[0].value.trim();
        //             var inv  = $('#invitees')[0].value.trim();
        //             var notes = $('#notes')[0].value.trim();

        //             $.post( "api/meetings/new", { name: name, date: date, location: loc, invitees: inv, notes: notes })
        //                 .done(function( data ) 
        //                 {
        //                     // alert( "Data Returned: " + data );
        //                     var json = $.parseJSON(data);
        //                     console.log(json['invitees']);
        //                     //Now send the meeting details to the other user.
        //                     var conn = peer.connect('08988b2ba7d538e9f43a780023098e1571e5a6d9');
        //                     // console.log('here');
        //                     conn.on('open', function()
        //                     {
        //                         console.log('sent');
        //                         conn.send(name+' '+date+' '+loc+' '+inv+' '+notes);
        //                     });
        //                 });
        //             }
        //       );
        // });

    });