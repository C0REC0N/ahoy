<audio id="squawk">
	  <source src="parrot.wav" type="audio/wav">
	</audio>

	<section id="box" class="box">
		<img src="parrot.png" alt="Parrot" id="parrot" onclick="controlHelp()">
		<br>
		<div id="message"></div>
	</section>
	
	<div id="help" onmouseleave="y = setTimeout(closeHelp,1000)" onmouseenter="clearTimeout(y)">
		<section>
			<br>
			
			<b class="powers">What is the nature of your question?</b>
			
			<p onclick="openWhat()"><i>What</i> is this?</p> <p onclick="openHow()"><i>How</i> do I do this?</p>
			
			<div id="what">
			
			<br>
			
			<b class="powers">Squawk</b>
			<p onclick="speech('I am Squawk, here to assist you and notify you of important matters!')">What are you?</p>
			
				<?php if($page == 'home') : ?>
				
					<b class="powers">Pirate's Powers</b>
					<p onclick="speech('A ship is a room to chat and collaborate with others')">What is a ship?</p>
					<p onclick="speech('2FA stands for Two Factor Authentication')">What is 2FA?</p>
					<p onclick="speech('A method to ensure it is definitely you logging in')">What is Two Factor Authentication?</p>
					
					<?php if($isPM == 'platform_manager') : ?>
						<b class="powers">Sea Captain's Powers</b>
						<p onclick="speech('The admin view that oversees the entire sea (website)')">What is the Sea Captain's Quarters?</p>
					
					<?php endif; ?>
				
				<?php endif; ?>
				
				<?php if($page == 'ship') : ?>
				
					<b class="powers">Pirate's Powers</b>
					<p onclick="speech('Sends you back to the home page')">What does Return to Sea do?</p>
					<p onclick="speech('A deck is our name for a file that has been shared')">What is a deck?</p>
					<p onclick="speech('A member is a fellow crew mate with no authority')">What is a member?</p>
					<p onclick="speech('A Quartermaster is like being an admin, they have more authority on the ship')">What is a Quartermaster?</p>
					<p onclick="speech('A Ship Captain is the owner of the ship, they have complete authority on the ship')">What is a Ship Captain?</p>
					<p onclick="speech('A Sea Captain has complete authority over every user and every ship')">What is a Sea Captain?</p>
					<p onclick="speech('Each role increases in authority and power; sharing powers of lesser roles')">What is the difference between the roles?</p>
					
					<?php if($isAdmin || $isOwner || $role == 'platform_manager') : ?>					

						<b class="powers">Quartermaster's Powers</b>
						<p onclick="speech('The Bridge is the admin view that oversees the ship')">What is The Bridge?</p>
					
					<?php endif; ?>
				
				<?php endif; ?>
				
				
				<?php if($page == 'task') : ?>
				
					<b class="powers">Pirate's Powers</b>
					<p onclick="speech('Sends you back to the ship page')">What does Return to Ship do?</p>
					<p onclick="speech('A quest is a task your ship wants to complete')">What is a Quest?</p>
					<p onclick="speech('A transparent quest means that quest has been completed')">Why is a quest transparent?</p>
				
				<?php endif; ?>
				
				<?php if($page == 'admin') : ?>
	
				<?php if(isset($shipID)) : ?>
					<b class="powers">Quartermaster's Powers</b>
					<p onclick="speech('Sends you back to the ship page')">What does Return to Ship do?</p>
				<?php endif; ?>
				<?php if(!isset($shipID)) : ?>
					<b class="powers">Sea Captain's Powers</b>
					<p onclick="speech('Sends you back to the home page')">What does Return to Sea do?</p>
				<?php endif; ?>
				
					<?php if(isset($shipID)) : ?>
						
						<p onclick="speech('Relevant information about your ship')">What are Ship Statistics?</p>
						<?php if ($role <> 'platform_manager') : ?>
							<p onclick="speech('Sends an invite to any available Sea Captain if you have a problem')">What does Request Sea Captain do?</p>
						<?php endif; ?>	
				
						<?php if($role == 'teacher' || $role == 'manager' || $role == 'platform_manager') : ?>
					
							<p onclick="speech('Allegations that another user has broken conduct')">What are User Reports?</p>
							<p onclick="speech('Allows you to edit how many reports a user has')">What does Manage User Reports do?</p>
							<p onclick="speech('Users with 10 or more reports are banned until their report count is lowered')">What happens when a user has too many reports?</p>
							<p onclick="speech('A request to become either a teacher, manager, or platform manager')">What are Role Requests?</p>
							<p onclick="speech('Allows you to change another user\'s role')">What does Manage User Role do?</p>
						
						<?php endif; ?>
						
						<?php if($isOwner || $role == 'platform_manager') : ?>
						
							<b class="powers">Ship Captain's Powers</b>
							<p onclick="speech('It will ask you for a username and send an invite to them (if valid)')">What does Invite Member do?</p>
							<p onclick="speech('It is where you can update details about your ship')">What does Manage Ship do?</p>		
							
						<?php endif; ?>						
					
					<?php endif; ?>
					
					<?php if($role == 'platform_manager') : ?>
						
						<?php if(isset($shipID)) : ?>
							<b class="powers">Sea Captain's Powers</b>
							
						<?php endif; ?>
						
						<?php if(!isset($shipID)) : ?>
							<p onclick="speech('Allegations that another user has broken conduct')">What are User Reports?</p>
							<p onclick="speech('Allows you to edit how many reports a user has')">What does Manage User Reports do?</p>
							<p onclick="speech('Users with 10 or more reports are banned until their report count is lowered')">What happens when a user has too many reports?</p>
							<p onclick="speech('A request to become either a teacher, manager, or platform manager')">What are Role Requests?</p>
							<p onclick="speech('Allows you to change another user\'s role')">What does Manage User Role do?</p>
						<?php endif; ?>
						<p onclick="speech('A request to visit a ship due to a cause of concern or functional error')">What are Presence Requests?</p>
						<p onclick="speech('Asks for a username and displays relevant information about them')">What does Search for User do?</p>
						<p onclick="speech('Relevant information about the entire sea (website)')">What are Site Statistics?</p>
					
					<?php endif; ?>
				
				<?php endif; ?>
			
			</div>
			
			<div id="how">
			
			<br>
		
			<?php if($page == 'home') : ?>
			
					<b class="powers">Pirate's Powers</b>
					<p onclick="speech('Press `Add New Ship` fill in the details')">How do I build a ship?</p>
					<p onclick="speech('Click on the ship you want to board')">How do I board a ship?</p>
					<p onclick="speech('Press your profile picture on the side bar')">How do I edit my profile?</p>
					<p onclick="speech('Press `Edit Account` on the side bar')">How do I change my username or password?</p>
					<p onclick="speech('Yes! Check if they have invited you by clicking `Check Invites`')">Can I join someone else's ship?</p>
					<p onclick="speech('Press `Check Invites` to check and I will also tell you if you do!')">Do I have any invites?</p>
					<p onclick="speech('Press `Enable 2FA` on the side bar')">How do I enable 2-factor authentication?</p>
					<p onclick="speech('Press `Logout` on the side bar')">How do I logout?</p>
					<p onclick="speech('Press `Toggle Notifications` below')">How do I turn off notifications?</p>
					<p onclick="speech('Press `Toggle Squawk` below')">How do I turn off the notification sound?</p>
					
					<?php if($isPM) : ?>
				
						<b class="powers">Sea Captain's Powers</b>					
						<p onclick="speech('Press `Captain\'s Quarters` on the left side bar')">How do I view my powers?</p>
					
					<?php endif; ?>
					
			<?php endif; ?>
			
			<?php if($page == 'ship') : ?>
			
				<b class="powers">Pirate's Powers</b>
				<p onclick="speech('Press `Return to Sea`')">How do I go back to home page?</p>
				<p onclick="speech('Click the AHOY! logo on the left side bar')">How do I disembark my ship?</p>
				<p onclick="speech('Click on a crew members name on the right side bar')">How do I view crew members?</p>
				<p onclick="speech('Press `Crew Chat` on the right side bar')">How do I message a crew member?</p>
				<p onclick="speech('Press `View Decks` on the left side bar')">How do I view my files for a ship?</p>
				<p onclick="speech('Press `Upload Deck` on the left side bar')">How do I upload a file for a ship?</p>
				<p onclick="speech('Press `Current Quests` on the left side bar')">How do I see my current tasks for a ship?</p>
				<p onclick="speech('Click `Leave Ship` on the left side bar')">How do I leave my ship?</p>
				
				<?php if($isAdmin || $role == 'platform_manager') : ?>
				
					<b class="powers">Quartermaster's Powers</b>					
					<p onclick="speech('Press `The Bridge` on the left side bar')">How do I view my powers?</p>
					
				<?php endif; ?>
				
				<?php if($isOwner || $role == 'platform_manager') : ?>
				
					<b class="powers">Ship Captain's Powers</b>					
					<p onclick="speech('Click on the member and press `Remove Member` on the right side bar')">How do I remove a member?</p>
					<p onclick="speech('Click on the member and press `Make Admin` on the right side bar')">How do I make a member an admin?</p>
					<p onclick="speech('Go to `The Bridge` and click `Invite Member`')">How do I invite someone?</p>	
					<p onclick="speech('Go to `The Bridge` and click `Manage Ship`')">How do I edit ship details?</p>
					<p onclick="speech('Go to `The Bridge` and click `Manage Ship`')">How do I delete my ship?</p>
					
				<?php endif; ?>
						
			<?php endif; ?>
			
			<?php if($page == 'task') : ?>
				
					<b class="powers">Pirate's Powers</b>
					<p onclick="speech('Press `Return to Ship`')">How do I go back to ship page?</p>
					<p onclick="speech('Press `Add New Quest` and fill in the details')">How do I create a quest?</p>
					<p onclick="speech('Click on a quest and select an option below')">How do I delete or complete a quest?</p>
					<p onclick="speech('Press `Close Quest`')">How close a quest?</p>
				
				<?php endif; ?>
			
			<?php if($page == 'admin') : ?>
					
				<?php if (isset($shipID)) : ?>
				
				<b class="powers">Quartermaster's Powers</b>
				<p onclick="speech('Press `Return to Ship`')">How do I go back to ship page?</p>
				<p onclick="speech('Press `Ship Statistics`')">How do I see information about my ship?</p>	

				<?php if ($role == 'manager' || $role == 'teacher' || $role == 'platform_manager') : ?>
			
					<p onclick="speech('Press `User Reports`')">How do I see a reports on users?</p>	
					<p onclick="speech('Press `Manage User Report`')">How do I edit the report count of a user?</p>	
					<p onclick="speech('Press `Role Requests`')">How do I see if someone wants to change their role?</p>	
					<p onclick="speech('Press `Manage User Role`')">How do I change the role of someone?</p>
					
				<?php endif; ?>	
				
				<?php if ($role <> 'platform_manager') : ?>
					<p onclick="speech('Press `Request PM`')">How do I request a Sea Captain?</p>	
				<?php endif; ?>					
				
				<?php if($isOwner || $role == 'platform_manager') : ?>
				
					<b class="powers">Ship Captain's Powers</b>
					<p onclick="speech('Press `Invite Member`')">How do I invite someone?</p>	
					<p onclick="speech('Press `Manage Ship`')">How do I edit ship details?</p>
					<p onclick="speech('Press `Manage Ship`')">How do I delete my ship?</p>
				
				<?php endif; ?>
				
				<?php endif; ?>
				
				<?php if($role == 'platform_manager') : ?>
				
					<b class="powers">Sea Captain's Powers</b>
					<?php if(!isset($shipID)) : ?>
						<p onclick="speech('Press `Return to Sea`')">How do I go back to home page?</p>
						<p onclick="speech('Press `User Reports`')">How do I see a reports on users?</p>	
						<p onclick="speech('Press `Manage User Report`')">How do I edit the report count of a user?</p>	
						<p onclick="speech('Press `Role Requests`')">How do I see if someone wants to change their role?</p>	
						<p onclick="speech('Press `Manage User Role`')">How do I change the role of someone?</p>
					<?php endif; ?>
					<p onclick="speech('Press `Presence Request`')">How do I see requests for platform managers?</p>
					<p onclick="speech('Press `Search for User`')">How do I search for a user?</p>
					<p onclick="speech('Press `Site Statistics`')">How do I see site wide data?</p>
				
				<?php endif; ?>
				
			<?php endif; ?>	
			
			</div>
		</section>
		<br>
		<button onclick="toggle()" id="alert" class="toggle">Toggle Squawk</button>
		<button onclick="notifications()" id="notif" class="toggle">Toggle Notifications</button>
	</div>
