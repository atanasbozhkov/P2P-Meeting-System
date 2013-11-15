### Meeting Display ###

1. Connect to swarm
2. Ask Server for the current version number of the list 
3. Server returns a hascode (e.g. 9aac)
4. Ask the swarm for the actual list
5. Recieve the list
6. Display the meetings

### Meeting Creation ###

We can safely assume that this is done after the user has filled all the details about the meeting.

1. Notify the server that a meeting is ready to be pushed.
2. When the queue is empty the server will give you a callback.
3. The server sends you the current hash.
4. Ask the swarm for the current file.
5. Update the file and give the hash back to the server.
