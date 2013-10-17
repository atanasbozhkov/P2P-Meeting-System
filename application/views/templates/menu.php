<div class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">P2P Meeting System</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="/">Home</a></li>
            <?php if (isset($username)) 
            { 
            	echo '<li><a href="/meetings">Meetings</a></li>';
        	} ?>
          </ul>
          <ul class="nav navbar-nav navbar-right">
                <li>
                <?php if (!isset($username)) 
                {
                	echo '<a href="/login">Log In</a></li> <li><a href="/register">Sign Up</a></li>';
                }else {
                	echo '<a href="/profile">'.$username.'</a></li>';
                	echo '<li><a href="/logout">Logout</a></li>';
                }
                ?></li>
          </ul>
        </div><!--/.navbar-collapse -->
      </div>
    </div>