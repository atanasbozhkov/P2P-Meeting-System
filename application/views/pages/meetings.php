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
    <input type="date" class="form-control" name="bday">
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
      <input id="textinput" name="textinput" type="text" placeholder="Times Square, New York" class="input-xlarge form-control">
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
  <button type="submit" class="btn btn-success">Create Meeting</button>
  </form>
  </div>