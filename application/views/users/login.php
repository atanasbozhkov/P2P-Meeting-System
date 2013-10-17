
<div class="container">
<div class='header'>
<h1> Login </h1>
</div>
<?php echo validation_errors(); ?>
<?php echo form_open('login',array('class' => 'form-signin')) ?>
	<form>
        <div class="form-group">
          <label for="exampleInputEmail1">Email address / Username</label>
          <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email" name="username">
        </div>
        <div class="form-group">
          <label for="exampleInputPassword1">Password</label>
          <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="password">
        </div>
        <button type="submit" class="btn btn-success">Log In</button>
	</form>

    </div> <!-- /container -->