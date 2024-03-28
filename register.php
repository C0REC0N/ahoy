<!DOCTYPE html>
<?php # REGISTRATION PAGE (COMPLETED FUNCTIONALITY)

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    # Connect to the database.
    require('connect_db.php');

    # Initialize an error array.
    $errors = array();

    # Check for a first name.
    if (empty($_POST['username'])) {
        $errors[] = 'Enter your username.';
    } else {
        $un = mysqli_real_escape_string($link, trim($_POST['username']));
    }

    # Check for an email address:
    if (empty($_POST['email'])) {
        $errors[] = 'Enter your email address.';
    } else {
        $e = mysqli_real_escape_string($link, trim($_POST['email']));
        # Validate email address format
        if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $e)) {
            $errors[] = 'Invalid email address.';
        }
    }

    # Check to see if a role has been chosen
    if (empty($_POST['roleChoice'])) {
        $errors[] = 'Please select a valid role';
    } else {
        $rc = mysqli_real_escape_string($link, trim($_POST['roleChoice']));
    }

    # Check for a password and matching input passwords.
    if (!empty($_POST['pass1'])) {
        if ($_POST['pass1'] != $_POST['pass2']) {
            $errors[] = 'Passwords do not match.';
        } else {
            $p = mysqli_real_escape_string($link, trim($_POST['pass1']));
        }
    } else {
        $errors[] = 'Enter your password.';
    }

# Check if email address already registered.
if (empty($errors)) {
  $checkQuery = "SELECT userID FROM user WHERE email='$e'";
  $checkResult = @mysqli_query($link, $checkQuery);

  $checkNameAvailability = "SELECT userID FROM user WHERE username='$un'";
  $checkNameResult = @mysqli_query($link, $checkNameAvailability);

  if ($checkResult) {
      if (mysqli_num_rows($checkResult) != 0) {
          $errors[] = 'Email address already registered.';
      }
  } else {
      $errors[] = 'Error checking email address registration: ' . mysqli_error($link);
  }

  if ($checkNameResult) {
    if (mysqli_num_rows($checkNameResult) != 0) {
        $errors[] = 'Username is already taken, please try another username.';
    }
} else {
    $errors[] = 'Error checking username registration: ' . mysqli_error($link);
}
}


    # If errors are empty, insert the new registered user into the user table
    if (empty($errors)) {
        $insertQuery = "INSERT INTO user (username, pass, email, roleChoice, reports) 
                        VALUES ('$un', SHA2('$p',256), '$e', '$rc', 0)";
        $insertResult = @mysqli_query($link, $insertQuery);

        if ($insertResult) {
             //If the users role is manager or teacher then
            if ($rc == "manager" || $rc == "teacher" || $rc == 'platform_manager') 
            {
				
				$IDQuery = "SELECT userID FROM user WHERE username = '$un' AND email = '$e'";
				$IDResult = mysqli_query($link, $IDQuery);
				$IDRow = mysqli_fetch_assoc($IDResult);
                
				$insertQuery = "INSERT INTO roleRequests (role, userID) VALUES ('$rc', ".$IDRow['userID'].")";
				$insertResult = mysqli_query($link, $insertQuery);
                
                //changing users role to be student if they are a teacher and to employee if they are a manager
                if($rc=='manager' || $rc == 'platform_manager'){
                    $newRole = 'employee';
					
                } else if($rc=='teacher'){
							$newRole = 'student';
						}
                    
                
                    $updateRole = "UPDATE user
                    SET roleChoice='$newRole'
                    WHERE email='$e'";
                    $insertResult = @mysqli_query($link, $updateRole);
     
            }

            echo "<script>
                    alert('You have successfully registered, you will be redirected to login')
                    window.location.href = 'login.php';
                </script>";
        } else {
            echo 'Registration failed.';
        }

        # Close database connection.
        mysqli_close($link);

        exit();
    }
    # Report errors.
    else {
        $cheese = "";
        foreach ( $errors as $msg ) {
            $cheese .= $msg ;
            $cheese .= " "; 
        }

        echo "<script>alert('Oops, looks like there is an issue: $cheese');</script>";

        # Close database connection.
        mysqli_close($link);
    }
}
?>


<html>
<head>
    <meta charset="utf-8">
    <title>Account Registration</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="login-page">

<img src="AHOYYYYY.png" alt="ahoy logo" class="centerlogo">

<form class="box" action="register.php" method="post" style="padding-top:20px !important; border-color:#004aad; background-color: #004aad;">
    <h1 style="text-align: center; color: #ffd79b; text-shadow: #ffd79b 0 0 20px; padding-bottom: 20px;">Account Registration</h1>

    <div class="row" style="justify-content: center">
        
      <label for="username"><b style="color: #ffd79b">Username</b></label>
      <input for="username" type="text" name="username" placeholder="Username" value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>">

      <label for="email"><b style="color: #ffd79b">Email</b></label>
      <input for="email" type="text" name="email" placeholder="Email" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>">

      <label for="password"><b style="color: #ffd79b">Password</b></label>
      <input for="pass1" type="password" name="pass1" placeholder="Password" value="<?php if (isset($_POST['pass1'])) echo $_POST['pass1']; ?>">
      <label for="password"><b style="color: #ffd79b">Repeat Password</b></label>
      <input for="pass2" type="password" name="pass2" placeholder="Confirm Password" value="<?php if (isset($_POST['pass2'])) echo $_POST['pass2']; ?>">


      <div class="smallrow" style="padding: 10px">
          <select id="roleChoice" name="roleChoice">
              <option value="">Role</option>
              <option value="manager" <?php if (isset($_POST['roleChoice']) && $_POST['roleChoice'] === 'manager') echo 'selected'; ?>>Manager</option>
              <option value="employee" <?php if (isset($_POST['roleChoice']) && $_POST['roleChoice'] === 'employee') echo 'selected'; ?>>Employee</option>
              <option value="teacher" <?php if (isset($_POST['roleChoice']) && $_POST['roleChoice'] === 'teacher') echo 'selected'; ?>>Teacher</option>
              <option value="student" <?php if (isset($_POST['roleChoice']) && $_POST['roleChoice'] === 'student') echo 'selected'; ?>>Student</option>
          </select>
      </div>

      <div class="bg" style="background-color: #437cd7; justify-content: center;">
        <div class="terms-box">
            <div class="terms-text">
                <h2>Terms and Conditions & Privacy Policy</h2>
                <p>Last Edited: 24/03/2024</p>
                <p>Ahoy Matey!</p>

                <p>1. Acceptance of Terms</p>
                <p>Welcome to AHOY!, a collaboration platform developed by Seven Seas Software ("the Company"). By accessing or using the AHOY! website ("the Site"), you agree to comply with and be bound by the following Terms and Conditions ("Terms"). Please read these Terms carefully before using the Site. If you do not agree with these Terms, you may not use the Site.</p>
                <p>2. User Registration</p>
                <p>2.1 In order to access certain features of the Site, you may be required to register for an account. By registering, you agree to provide accurate and complete information.</p>
                <p>2.2 You are responsible for maintaining the confidentiality of your account credentials, including your username and password. You agree to notify the Company immediately of any unauthorized use of your account.</p>
                <p>3. Data Collection and Usage</p>
                <p>3.1 AHOY! collects and stores the following user data: email address, password, and username.</p>
                <p>3.2 Chat logs, files, and drawings uploaded or created by users on the Site are stored in our database.</p>
                <p>3.3 The Company ensures that user data is kept safe and secure and complies with GDPR regulations.</p>
                <p>3.4 User data, including chat logs and media files, may be used by the Company to investigate reports of anti-social behavior or violations of the Terms outlined herein. Such investigations may result in users being banned from the Site.</p>
                <p>4. Intellectual Property</p>
                <p>4.1 Users retain ownership of any content they upload or create on the Site.</p>
                <p>4.2 By uploading or creating content on the Site, users grant the Company a non-exclusive, royalty-free license to use, reproduce, modify, adapt, publish, translate, distribute, and display such content for the purposes of operating and improving the Site.</p>
                <p>5. Prohibited Conduct</p>
                <p>5.1 Users agree not to engage in any conduct that violates these Terms or the rights of others.</p>
                <p>5.2 Prohibited conduct includes but is not limited to:</p>
                <p>a) Transmitting any content that is unlawful, harmful, threatening, abusive, harassing, defamatory, vulgar, obscene, or otherwise objectionable.</p>
                <p>b) Impersonating any person or entity, or falsely stating or misrepresenting your affiliation with a person or entity.</p>
                <p>c) Uploading or transmitting any viruses or other harmful code.</p>
                <p>d) Attempting to gain unauthorized access to the Site or its systems.</p>
                <p>6. Termination</p>
                <p>6.1 The Company reserves the right to suspend or terminate access to the Site for any user who violates these Terms or engages in prohibited conduct.</p>
                <p>6.2 Upon termination, all rights and licenses granted to the user will immediately cease.</p>
                <p>7. Disclaimer of Warranties</p>
                <p>7.1 The Site is provided on an "as is" and "as available" basis, without any warranties of any kind, either express or implied.</p>
                <p>7.2 The Company does not warrant that the Site will be uninterrupted, secure, or error-free, or that any defects will be corrected.</p>
                <p>7.3 Users acknowledge that they use the Site at their own risk.</p>
                <p>8. Limitation of Liability</p>
                <p>8.1 To the fullest extent permitted by law, the Company shall not be liable for any indirect, incidental, special, consequential, or punitive damages, including but not limited to loss of profits, data, or goodwill.</p>
                <p>8.2 In no event shall the total liability of the Company exceed the amount paid by the user, if any, for accessing or using the Site.</p>
                <p>9. Governing Law</p>
                <p>9.1 These Terms shall be governed by and construed in accordance with the laws of Edinburgh, without regard to its conflict of law principles.</p>
                <p>9.2 Any disputes arising out of or in connection with these Terms shall be subject to the exclusive jurisdiction of the courts of Edinburgh.</p>
                <p>10. Modifications to Terms</p>
                <p>10.1 The Company reserves the right to modify or revise these Terms at any time without prior notice.</p>
                <p>10.2 Users are responsible for regularly reviewing these Terms. Continued use of the Site after any modifications indicates acceptance of the updated Terms.</p>
                <p>By using AHOY!, you acknowledge that you have read, understood, and agree to be bound by these Terms and Conditions. If you do not agree with any part of these Terms, please do not use the Site. If you have any questions or concerns regarding these Terms, please contact us at sevenseassoftware123@gmail.com.</p>
                <p>AHOY! Privacy Policy</p>
                <p>1. Introduction</p>
                <p>Welcome to AHOY!, a collaboration platform developed by Seven Seas Software ("the Company"). This Privacy Policy outlines how we collect, use, and disclose personal information when you use the AHOY! website ("the Site"). By accessing or using the Site, you consent to the practices described in this Privacy Policy.</p>
                <p>2. Information We Collect</p>
                <p>2.1 Personal Information: When you register for an account on AHOY!, we collect personal information such as your email address, password, and username.</p>
                <p>2.2 User Content: We collect chat logs, files, and drawings uploaded or created by users on the Site.</p>
                <p>2.3 Automatically Collected Information: We may collect certain information automatically when you visit the Site, including your IP address, browser type, operating system, and device information.</p>
                <p>3. Use of Information</p>
                <p>3.1 Providing and Improving the Service: We use the information collected to provide and improve the AHOY! service, including troubleshooting, data analysis, testing, and research.</p>
                <p>3.2 Communication: We may use your email address to send you important notifications about your account or changes to our services.</p>
                <p>3.3 Investigations: We may use user data, including chat logs and media files, to investigate reports of anti-social behavior or violations of our Terms of Service.</p>
                <p>4. Data Sharing and Disclosure</p>
                <p>4.1 Third-Party Service Providers: We may share personal information with third-party service providers who assist us in providing the AHOY! service, such as hosting providers or analytics services.</p>
                <p>4.2 Legal Compliance: We may disclose personal information when required by law or to protect the rights, property, or safety of the Company, our users, or others.</p>
                <p>5. Data Security</p>
                <p>5.1 Security Measures: The Company takes reasonable measures to protect the security of your personal information and prevent unauthorized access, disclosure, or modification.</p>
                <p>5.2 Data Retention: We retain personal information for as long as necessary to fulfill the purposes outlined in this Privacy Policy, unless a longer retention period is required by law.</p>
                <p>6. User Rights</p>
                <p>6.1 Access and Correction: You have the right to access and correct your personal information held by AHOY!. You can update your account information through the Site.</p>
                <p>6.2 Account Deletion: You may request the deletion of your account and associated personal information by contacting us at sevenseassoftware123@gmail.com.</p>
                <p>7. Children's Privacy</p>
                <p>AHOY! is not intended for children under the age of 13. We do not knowingly collect personal information from children under 13. If you believe that we have inadvertently collected information from a child under 13, please contact us immediately.</p>
                <p>8. Changes to this Privacy Policy</p>
                <p>The Company reserves the right to modify or revise this Privacy Policy at any time. We will notify users of any material changes by posting the updated Privacy Policy on the Site.</p>
                <p>9. Contact Us</p>
                <p>If you have any questions or concerns about this Privacy Policy or our data practices, please contact us at sevenseassoftware123@gmail.com.</p>

                <p>By using AHOY!, you acknowledge that you have read, understood, and agree to be bound by this Privacy Policy. If you do not agree with any part of this Privacy Policy, please do not use the Site.</p>


                <h4>I agree to the <span> Terms and Conditions </span>and have read the Privacy Notice</h4>
                <input type="checkbox" name="agree" id="agree" value="agree" style="position: relative" required/>
            </div>
        </div>
      </div>

      <button class="login-button two" type="submit">Register</button>
    </div>

    <div class="row">
        <p style="text-align: center; color: #ffd79b;">Already have an account?</p>
        <a href="login.php" style="color:#d49307">Login here</a>
    </div>
</form>

</body>

<!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXz2htPH01sSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+TbbvYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Piv4xVNRyMGpqks2by6br4gND7DXjqke9RmUpD8jgGtD72P9yug3goQfGIIeyAns"
        crossorigin="anonymous"></script>
</html>
