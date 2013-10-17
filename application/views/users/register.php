
<div class="container">
<div class='header'>
<h1> Create a new accont </h1>
</div>
<?php echo validation_errors(); ?>
<?php echo form_open('register',array('class' => 'form-signin')) ?>
	<form>
        <div class="form-group">
          <label for="exampleInputEmail1">Email address / Username</label>
          <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email" name="username">
        </div>
        <div class="form-group">
          <label for="exampleInputPassword1">Password</label>
          <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="password">
          <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Repeat Password" name="repeat">
        </div>
        <button type="submit" class="btn btn-success">Register</button>
	</form>

    </div> <!-- /container -->