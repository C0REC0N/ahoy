	var squawk = true; //Initialises the squawk audio to on
	var helpOpen = false; //Initialises help menu to closed
	var bigSquawk = false; //Initialises squawk to not big
	var notif = true; //Initialise notifications to on
	
	function hide(){ //Hides the speech bubble
	var x = document.getElementById("message");
	var y = document.getElementById("box");
			x.style.opacity = "0";
			x.style.fontSize = "0px";
			x.style.width = "auto";
			x.style.height = "auto";
			x.style.padding = "20px";
			setTimeout(y.style.width, 1000,"auto");

	}
	
	function welcome(register, ship, msg){ //If the user is new, they will be given an introduction. If not, the parrot is set to his default location. Sends optional notification through.
	var x = document.getElementById("message");
	var y = document.getElementById("parrot");
	var z = document.getElementById("box");
	var h = document.getElementById("help");
	y.style.display = "block";
	x.style.display = "block";
	if(ship){
		
		z.classList.add("ship");
		h.classList.add("ship");
	} else {
		
		z.classList.add("home");
		h.classList.add("home");
	}
	
	if(!register){
		hide();
		x.classList.add("message");
		y.classList.add("parrot");
		
		if (msg.length > 0){
			speech(msg,true);
		}
	} else {
		x.classList.add("newMessage");
		y.classList.add("newParrot");
		x.innerHTML = "Ahoy, Matey! Welcome to the seven seas! I am Squawk, your First Mate, and I am here to let you when you have new messages, when you have performed an important action, or to help guide you through your quests. Just give me a tap! <a onclick='shrink()'> Let's Begin</a>";
		z.style.backdropFilter = "blur(5px)";
		z.style.width = "auto";
		bigSquawk = true;
	}
	}
	
	function shrink(){ //Shrinks the parrot from the introductory size, to default
	bigSquawk = false;
	var x = document.getElementById("parrot");
	var y = document.getElementById("message");
	var z = document.getElementById("box");
		y.style.display = "none";
		x.style.width = "100px";
		x.style.height = "auto";
		x.style.cursor = "pointer";
		y.classList.remove("newMessage");
		z.style.width = "auto";
		z.style.backdropFilter = "none";
		setTimeout(hide,500);
		setTimeout(block,1000);
	}
	
	function block(){ //Makes the message box appear (it is a function to allow delay)
		document.getElementById("message").style.display = "block";
	}
	
	function speech(text, message){	
	if (notif || !message){
		var x = document.getElementById("message");
		if (x.style.display != "none"){
			if (x.style.opacity == "0") {
				x.style.opacity = "1";
				x.style.top = "1px"
				x.style.right = "100px"
				x.style.fontSize = "15px";
				x.innerHTML = text;
			
				if (squawk){
					play();
				}
				if (message){
					
					document.getElementById("box").style.width = "75%";
					setTimeout(hide,2500);
				} else {
					document.getElementById("box").style.width = "50%";
				}
			} else {
				if (!message){
					hide();
				}
				setTimeout(speech, 500, text, message);
			}
		} else {
			
			setTimeout(speech, 500, text, message);
		}
	}
	}

	
	function controlHelp(){ //Controls the help menu
	var x = document.getElementById("message");
	if (x.style.opacity == "0"){
		if (!bigSquawk) {
			if (helpOpen) {
				closeHelp();
			} else{
				openHelp();
			}
		}
	}
	}
	
	function openHelp() { //Opens the help menu
	  document.getElementById("help").style.width = "250px";
	  document.getElementById("box").style.margin = "0px 250px 0px 0px";
	  helpOpen = true;
	}

	function closeHelp() { //Closes the help menu
		document.getElementById("help").style.width = "0px";
		document.getElementById("box").style.margin = "5px";
		helpOpen = false;
		hide();	
	}
	
	function play() { //Plays parrot squawk
		document.getElementById("squawk").play();
	}
	
	function toggle(){ //Toggles the squawk sound
	var x = document.getElementById("alert");	
		if (squawk){
			squawk = false;
			x.style.backgroundColor = "#f93333";
		} else {
			squawk = true;
			x.style.backgroundColor = "#30bb8a";
		}
	}
	
	function notifications(){ //Toggles notifications
	var x = document.getElementById("notif");	
		if (notif){
			notif = false;
			x.style.backgroundColor = "#f93333";
		} else {
			notif = true;
			x.style.backgroundColor = "#30bb8a";
		}
	}

	function openWhat(){
	
		document.getElementById("what").style.display = "block";
	}

	function openHow(){
		
		document.getElementById("how").style.display = "block";
	}

	var hasSeen = false;

	function setHasSeen(arg){
		
		hasSeen = arg;
	}
	
	function hasSeen(){
		
		return hasSeen;
	}
