### P2P Web Meeting System ###

Based on:

1. Code Igniter
2. Twitter Bootstrap 
3. Peer.js

### Users ###
Usernames and Passwords are kept on the PHP/MYSQL server. 
The schema is:
Users:

    1.(int): id
    2.(str): username
    3.(str): sha1(password)

### Peers ###
Every peer is initilised with an ID (used by the brokering service) and a token to connect to the NodeJS server.
A typical connection would be:

    <script type="text/javascript">
    var user = '<?php echo sha1($username);?>';
    var peer = new Peer(user, {key: '5jrhcy14ieezh0k9'});
    var conn = peer.connect('second');
    conn.on('open', function(){
        conn.send('WOW!');
    });
    </script>

Here every user is connected using the token: `5jrhcy14ieezh0k9`
and for the peerID the sha1 of the user's email is taken.
NOTE: There must be no special symbols (including .&@) in the peerID.
Todo:

1. class="active" dynamic on menu
