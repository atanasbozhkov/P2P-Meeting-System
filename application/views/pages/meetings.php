  <script type="text/javascript" src="<?php echo asset_url('meetings.js','js');?>">
  </script>

  <script type="text/javascript">

    function pushMeeting () {
      console.log('Invoked');
      console.log(email);
      //Dirty little hack for getting the email to the database.
      document.getElementById('manager').value = email;
      document.getElementById('user').value = email;
      // console.log(document.getElementById('manager').value + 'HI');
      // console.log(document.getElementById('user').value + 'HI');
      var invs = document.forms[0].invitees.value.split(',');
      console.log(invs)
      for (var i = 0; i < invs.length; i++) {
        document.forms[0].invitees.value = invs[i];
        console.log($("#newMeeting").serialize());
        $.post( "/api/meetings/put", $("#newMeeting").serialize(), function(data) { alert('Meeting created succsessfully.');});
      };
      //Push the meeting to the distance vector peers.
      for (var i = 0; i < closestToSelf.length; i++) {

        var contactee = closestToSelf[i].node;
        var conn = peer.connect(CryptoJS.SHA1(contactee).toString());
        console.log(CryptoJS.SHA1(contactee).toString());

        conn.on('open', function(){
        //RPC Request for own details.
        conn.send({rpc:'saveNewData', meeting:$("#newMeeting").serialize()});
        console.log('sent RPC to '+ contactee);
        });

        conn.on('data', function(data){
          //Finally - return the user's data
          console.log(data);
        });
    }
  }
  </script>
	<br/><br/><br/>

  <div id="meetings">
Current meetings
  </div>
  <div style='width:300px;margin:0 auto;'>
  <form id="newMeeting">
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
  <input type="hidden" id="manager" name="manager" value="">
  <input type="hidden" id="user" name="user" value="">
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
  <button id="submitter" type="button" class="btn btn-success" onclick="pushMeeting()">Create Meeting</button>
  </form>
  </div>