//Define constants
const IdLenth = 20;
const BucketSize = 20;

//Array.move Prototype
Array.prototype.move = function (old_index, new_index) {
    if (new_index >= this.length) {
        var k = new_index - this.length;
        while ((k--) + 1) {
            this.push(undefined);
        }
    }
    this.splice(new_index, 0, this.splice(old_index, 1)[0]);
    return this; // for testing purposes
};

//Function taken from crypto.js
function hexToBytes(hex) {
                for (var bytes = [], c = 0; c < hex.length; c += 2)
                        bytes.push(parseInt(hex.substr(c, 2), 16));
                return bytes;
}

//Function taken from crypto.js
function bytesToHex(bytes) {
                for (var hex = [], i = 0; i < bytes.length; i++) {
                        hex.push((bytes[i] >>> 4).toString(16));
                        hex.push((bytes[i] & 0xF).toString(16));
                }
                return hex.join("");
}

function NewNodeID(string){
	decoded = hexToBytes(string); //conv to byte array
	var ret = []
	for (var i = 0; i< IdLenth; i++) {
		ret[i] = decoded[i] //Decode each digit
	};

	return ret;
}

function NewRandomNodeID(){
	var ret = [];
	for (var i = 0; i < IdLenth; i++) {
		ret[i] = Math.floor((Math.random()*256)); //between 1 and 256
	};
	return ret;
}

function Equals(node1, node2){
	for (var i = 0; i< IdLenth; i++) {
		if (node1[i] != node2[i]) {
			return false;
		};
	}
	return true;
}

function Less (node1, node2) {
	for (var i = 0; i < IdLenth; i++){
		if (node1[i] != node2[i]){
			return node1[i] < node2[i];
		}
	}
	return false;
}

function Xor(node1,node2){
	var ret = [];
	for(var i = 0; i < IdLenth; i++){
		ret[i] = node1[i]^node2[i];
	}
	return ret;
}

//Number of leading zeros
function PrefixLen(node){
	for(var i = 0; i < IdLenth; i++){
		for(var j = 0; j<8; j++){
			if ((node[i] >> 7-j & 0x1) != 0){
				return i * 8 + j;
			}
		}
	}
	return IdLenth * 8 - 1;
}

function NewRoutingTable(node){
	//Define the routing table struct;
	var routingTable = {
		'buckets':[],
		node:node
	};

	//Create the buckets
	for(var i=0; i < IdLenth * 8; i++){
		routingTable.buckets[i] = []
	}
	//and the node
	routingTable.node = node;
	return routingTable;
}


function Update(node, table)
{
	var found = false;
	var prefix_len = PrefixLen(node);
	//DEBUG
	console.log('The prefix length is:'+ prefix_len);
	bucket = table.buckets[prefix_len];
	//DEBUG
	console.log('Bucket is:'+ bucket+'With length of:'+bucket.length);
	//First chech wether the node is already contained within the bucket.
	table.buckets[prefix_len].map(function(x){
		if (Equals(x, node))
		{
			var pos = table.buckets[prefix_len].indexOf(x);
			table.buckets[prefix_len].move(pos,0);
			//DEBUG
			console.log('Moved from pos['+pos+'] to 0 with x:'+x);
			console.log(table.buckets[prefix_len]);
			found = true;
		}});
	if (found == false){
		//Now - if it's not in the bucket - check wether the bucket is full.
		//DEBUG
		console.log('The element is not in the bucket yet.');
		if(table.buckets[prefix_len].length < BucketSize){
			//DEBUG
			console.log('The bucket is not full yet. => Pushing the element in the bucket.');
			table.buckets[prefix_len].push(node);
			// console.log("Pushed. The bucket is now:"+bucket);
		    // TODO: Handle insertion when the list is full by evicting old elements if
		    // they don't respond to a ping.
	}
	}
	// table.buckets[prefix_len] = bucket;
}

function copyToVector(bucket, target, vector){
	for (var i = 0; i < bucket.length; i++) {
		console.log('copyToVector: The bucket is:'+bucket[i]);
		console.log('copyToVector: The distance is: '+Xor(bucket[i], target))
		vector[i] = {node:bucket[i],distance:Xor(bucket[i], target)};
	};
	return vector;
}

function FindClosest (node,count, table){
	var vector = [];
	console.log('Vector created')
	bucket_num = PrefixLen(Xor(node, table.node));
	console.log('Bucket number is:'+bucket_num)
	bucket = table.buckets[bucket_num];
	console.log('The bucket is:'+bucket)
	copyToVector(bucket, node, vector);
	console.log('vector has been modified')
	for (var i = 1; (bucket_num - i >= 0 || bucket_num + i < IdLenth * 8) && vector.length < count; i++) {
		if (bucket_num - i >= 0) {
			bucket = table.buckets[bucket_num - i];
			copyToVector(bucket, node, vector);
		};
		if (bucket_num < IdLenth * 8) {
			bucket = table.buckets[bucket_num + i];
			copyToVector(bucket, node, vector);
		};
	};
	// vector.distance.sort(function(a,b){b-a});
	// vector.contact.sort
	vector.sort(function(a,b){bytesToHex(a.distance) -bytesToHex(b.distance)});
	return vector;
}

function testUpdate(){
	var mynode = NewRandomNodeID();
	var tab = NewRoutingTable(mynode);
	for (var i = 0; i < 100; i++) {
		Update(NewRandomNodeID(), tab);
	};
	return tab;
}

function testDistance()
{
	var tab = testUpdate();
	var findMe = NewRandomNodeID()
	var vecD = FindClosest(findMe, BucketSize, tab);
	return vecD;
}

function tests()
{
	// Equals test
	node1 = NewRandomNodeID();
	node2 = NewRandomNodeID();
	if(Equals(node1, node2) == false){
		if (Equals(node1, node1) == true) {
			console.log('Equals: Passed');
		};
	}
	//End of Equals test
}