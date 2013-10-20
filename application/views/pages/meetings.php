  <script type="text/javascript">
    $(document).ready(function() {
        var request = $.ajax({
        url: "/",
        context: document.body
        }).done(function() {
            // console.log(request.responseText);
        })
        //End of AJAX Request
        $(function() {
              $("#submitter").click( function()
                   {
                    //Gather all the values and post them via API to /api/meeting/new
                    // alert('button clicked');
                    var name = $('#name')[0].value.trim();
                    var date = $('#date')[0].value.trim();
                    var loc  = $('#location')[0].value.trim();
                    var inv  = $('#invitees')[0].value.trim();
                    var notes = $('#notes')[0].value.trim();

                    $.post( "api/meetings/new", { name: name, date: date, location: loc, invitees: inv, notes: notes })
                        .done(function( data ) 
                        {
                            // alert( "Data Returned: " + data );
                            var json = $.parseJSON(data);
                            console.log(json['invitees']);
                            //Now send the meeting details to the other user.
                            var conn = peer.connect('08988b2ba7d538e9f43a780023098e1571e5a6d9');
                            // console.log('here');
                            conn.on('open', function()
                            {
                                console.log('sent');
                                conn.send(name+' '+date+' '+loc+' '+inv+' '+notes);
                            });
                        });
                    }
              );
        });

    });

  </script>
	<br/><br/><br/>
  <div style='width:300px;margin:0 auto;'>
  <fieldset>

  <!-- Form Name -->
  <legend>Organise a new meeting</legend>

  <!-- Text input-->
  <div class="form-group">
    <label class="control-label" for="name">Name</label>
    <div class="controls">
      <input id="name" name="name" type="text" placeholder="Meeting Name" class="input-xlarge form-control">
    </div>
  </div>

  <div class="form-group">
    <label for="datePicker">Date</label>
    <input id="date" type="date" class="form-control" name="date">
  </div>

  <!-- Search input-->
  <div class="form-group">
    <label class="control-label" for="invitees">Invitees</label>
    <div class="controls">
      <input id="invitees" name="invitees" type="text" placeholder="Add People by Email" class="input-xlarge search-query form-control">
    </div>
  </div>

  <!-- Text input-->
  <div class="form-group">
    <label class="control-label" for="textinput">Location</label>
    <div class="controls">
      <input id="location" name="location" type="text" placeholder="Times Square, New York" class="input-xlarge form-control">
    </div>
  </div>

  <!-- Textarea -->
  <div class="form-group">
    <label class="control-label" for="notes">Notes</label>
    <div class="controls">                     
      <textarea id="notes" name="notes" class="form-control" >
  </textarea>
    </div>
  </div>

  </fieldset>
  <button id="submitter" type="submit" class="btn btn-success">Create Meeting</button>
  </form>
  </div>