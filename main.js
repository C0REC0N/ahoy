//canvas functionality
const canvas = document.getElementById("my-canvas");

canvas.height = window.innerHeight;
canvas.width = window.innerWidth;

const reader = new FileReader();
const img = new Image();

const ctx = canvas.getContext("2d");
let lineWidth = 5;
ctx.lineWidth = lineWidth;

const clearBtn = document.getElementById("clear-btn");
const redBtn = document.getElementById("red-btn");
const blueBtn = document.getElementById("blue-btn");
const greenBtn = document.getElementById("green-btn");
const blackBtn = document.getElementById("black-btn");
const colorPicker = document.getElementById("color-picker");
const buttonContainer = document.getElementById("button-container");

let drawing = false;
let x1, y1; 

buttonContainer.addEventListener('change', e => {
    ctx.lineWidth = e.target.value; 
})

colorPicker.addEventListener("blur", (e) => {
    ctx.strokeStyle= e.target.value;
})

clearBtn.addEventListener("click", () => {
    ctx.clearRect(0,0,canvas.width, canvas.height);
})
redBtn.addEventListener("click", () => {
    ctx.strokeStyle="#FF0000";
    colorPicker.value = "#FF0000"
})
blueBtn.addEventListener("click", () => {
    ctx.strokeStyle="#0000FF";
    colorPicker.value = "#0000FF"
})
greenBtn.addEventListener("click", () => {
  ctx.strokeStyle="#00FF00";
  colorPicker.value = "#00FF00"
})
blackBtn.addEventListener("click", () => {
    ctx.strokeStyle="black";
    colorPicker.value = "#000000"
})

canvas.addEventListener("mousedown", (e) => {
    init(e);
})
canvas.addEventListener("mousemove", (e) => {
    draw(e);
})

canvas.addEventListener("mouseup", (e) => {
    drawing = false;
})

function init(e) {
    x1 = e.offsetX; 
    y1 = e.offsetY;
    drawing = true;
}


function draw(e) {
    if (drawing) {
        ctx.lineCap = 'round';
        ctx.beginPath(); 
        ctx.moveTo(x1, y1)
        ctx.lineTo(e.offsetX,e.offsetY);
        ctx.stroke();
        ctx.closePath()
        x1 = e.offsetX; 
        y1 = e.offsetY;
    }
}

const uploadImage = (e) => {
    reader.onload = () => {
        img.onload = () => {
            ctx.drawImage(img, 0, 0);
        };
        img.src = reader.result;
    };
    reader.readAsDataURL(e.target.files[0]);
};

const imageLoader = document.getElementById("uploader");
imageLoader.addEventListener("change", uploadImage);

document.getElementById('buttonup').addEventListener('click', openDialog);

function openDialog() {
  document.getElementById('uploader').click();
}

function download() {
    const image = canvas.toDataURL();
    const link = document.createElement("a");
    link.href = image;
    link.download = "image.png";
    link.click();
}
  
document.getElementById("buttondown").addEventListener("click", download);

/* open the chat */
function openNav() {
	document.getElementById("chatNav").style.display = "block";
	document.getElementById("chatNav").style.width = "250px";
  }
  
/* Close the chat */
function closeNav() {
	document.getElementById("chatNav").style.display = "none";
	document.getElementById("chatNav").style.width = "0px";
} 

function openForm() {
    document.getElementById("myForm").style.display = "block";
}

function closeForm() {
    document.getElementById("myForm").style.display = "none";
}

function openAcc() {
    document.getElementById("accountForm").style.display = "block";
    $('div.side').css("filter", "blur(2px)");
}

function closeAcc() {
    document.getElementById("accountForm").style.display = "none";
    $('div.side').css("filter", "none");
}

function openMan() {
    document.getElementById("accountForm").style.display = "block";
  
}

function closeMan() {
    document.getElementById("accountForm").style.display = "none";
   
}

function confirmLeave() {
    if (confirm("Are you sure you want to leave this ship?")) {
        document.getElementById("leaveShipForm").submit();
    }
}


//functions for opening and closing the member invite button on ship_page.php
function openInviteForm() {
    document.getElementById("inviteForm").style.display = "block";
   
}

function closeInviteForm() {
    document.getElementById("inviteForm").style.display = "none";
  
}

function openPrivateChat() {
        document.getElementById('recipientUsername').value = crewUsername;
        document.getElementById('openPrivateChat').submit();
    
}

//function for opening and closing report form for a user
function openReportMember() {
    document.getElementById("reportMemberForm").style.display = "block";
    $('div.side').css("filter", "blur(2px)");

    
    document.getElementById("userSection").style.display = "none";
    $('div.side').css("filter", "blur(2px)");
}

function closeReportMember() {
    document.getElementById("reportMemberForm").style.display = "none";
    $('div.side').css("filter", "none");

    document.getElementById("userSection").style.display = "block";
    $('div.side').css("filter", "blur(2px)");
}


//functions for opening and closing the report member button on ship_page.php
function openReportForm() {
    document.getElementById("reportForm").style.display = "block";
    $('div.side').css("filter", "blur(2px)");
}

function closeReportForm() {
    document.getElementById("reportForm").style.display = "none";
    $('div.side').css("filter", "none");
}

//functions for opening and closing the "open invites" button on ship_select.php page
function openInvites() {
    document.getElementById("invitationList").style.display = "block";
    $('div.side').css("filter", "blur(2px)");
}

function closeInvites() {
    document.getElementById("invitationList").style.display = "none";
    $('div.side').css("filter", "none");
}

//functions for opening and closing the "open invites" button on ship_select.php page
function openPendingMembers() {
    document.getElementById("pendingMembers").style.display = "block";
    $('div.side').css("filter", "blur(2px)");
}

function closePendingMembers() {
    document.getElementById("pendingMembers").style.display = "none";
    $('div.side').css("filter", "none");
}

function open2FA() {
    document.getElementById("2FAForm").style.display = "block";
    $('div.side').css("filter", "blur(2px)");
}

function close2Fa() {
    document.getElementById("2FAForm").style.display = "none";
    $('div.side').css("filter", "none");
}

function delShip() {
	window.location.href = 'ship_select.php';
}

function openCrew() {
    document.getElementById("crewForm").style.display = "block";
}

function closeCrew() {
    document.getElementById("crewForm").style.display = "none";
}

function openFiles() {
    document.getElementById("fileList").style.display = "block";
}

function closeFiles() {
    document.getElementById("fileList").style.display = "none";
}

function openUploadFiles() {
    document.getElementById("uploadFile").style.display = "block";
}

function closeUploadFiles() {
    document.getElementById("uploadFile").style.display = "none";
}

function openCrewMember(memberName, isAdmin, isOwner) {
    var crewMemberPopup = document.getElementById("crewMember");
    var memberNameField = crewMemberPopup.querySelector("#memberName");
    var memberStatusField = crewMemberPopup.querySelector("#memberStatus");

    memberNameField.textContent = memberName;
    crewUsername = memberName;

    if (isOwner) {
        memberStatusField.textContent = "Owner";
    } else if (isAdmin) {
        memberStatusField.textContent = "Admin";
    } else {
        memberStatusField.textContent = "Member";
    }    
    crewMemberPopup.style.display = "block";
    crewMemberPopup.style.right = "0";

}

function removeMember() {
    document.getElementById('usernameToRemove').value = crewUsername;
    document.getElementById('removeMemberForm').submit();
}

function reportMember() {
    document.getElementById('usernameToReport').value = crewUsername;
    document.getElementById('reportMemberForm').submit();
}

function makeAdmin() {
    document.getElementById('crewAdminName').value = crewUsername;
    document.getElementById('makeAdminForm').submit();
}


function closeCrewMember() {
    document.getElementById("crewMember").style.display = "none";
}


function confDelShip() {
  	if (confirm("Are you sure you want to destroy your ship?") == true) {
		delShip()
  	}
}

function ReturnToShip() {
    window.location.href = 'ship_page.php';
}

function openTasks() {
    window.location.href = 'task_select.php';
}

function openTaskPopup(b) {

	document.getElementById(b.toString()).style.display = "block";
}

function closeTaskPopup(b) {
    document.getElementById(b.toString()).style.display = "none";
}

function MReportOpen(){
	
	document.getElementById("MReport").style.display = "block";
}

function MReportClose(){
	
	document.getElementById("MReport").style.display = "none";
}

function MRoleOpen(){
	

	document.getElementById("MRole").style.display = "block";
}

function MRoleClose(){
	

	document.getElementById("MRole").style.display = "none";
}

function shipSearchOpen(){
	

	document.getElementById("shipSearch").style.display = "block";
}

function shipSearchClose(){
	

	document.getElementById("shipSearch").style.display = "none";
}

function userSearchOpen(){
	

	document.getElementById("userSearch").style.display = "block";
}

function userSearchClose(){
	

	document.getElementById("userSearch").style.display = "none";
}

function requestsOpen(){
	

	document.getElementById("requests").style.display = "block";
}

function requestsClose(){
	

	document.getElementById("requests").style.display = "none";
}

function openReport(){
	
	document.getElementById("reportForm").style.display = "block";
}

function closeReport(){
	
	document.getElementById("reportForm").style.display = "none";
}

function reportsOpen(){
	
	document.getElementById("reports").style.display = "block";
}

function reportsClose(){
	
	document.getElementById("reports").style.display = "none";
}

function roleRequestsOpen(){
	

	document.getElementById("roleRequests").style.display = "block";
}

function roleRequestsClose(){
	

	document.getElementById("roleRequests").style.display = "none";
}
