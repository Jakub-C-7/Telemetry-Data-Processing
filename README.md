<b><u><h1>Telemetry Data Processing - Secure Web-Application Development</h1></u></b>

<h2>Installation and Execution Instructions (For a local P3T PHP environment): </h2>
1. Place the 'coursework' folder into your 'includes' directory.
2. Place the 'coursework_public' folder into your 'public_php' directory.
3. Edit lines 48 and 49 in the 'settings.php' file in the 'coursework' folder to include your username and password of an EE M2M server account.
4. Execute the 'AA_telemetryapplication_db' SQL script in the MariaDB console to create the database.

<h2>User Experience:</h2>

Upon starting the application, users see the starting menu homepage and have access to a navigation bar with the following options: Home, Register, and Login. A user must create an account via the 'Register' page and log-in using the 'Login' page to use the telemetry platform. An account has to be created with several valid inputs. The account must have a valid email address, a valid UK mobile number in a format starting with '44' followed by the rest of the mobile number (without the starting '0' and 12 digits in total), a password which is at least 8 characters long, contains at least one uppercase letter, one lowercase letter, and one number, and ensure that the 'Confirm Password' field directly matches the 'Password' field.

Once logged-in, users see a homepage, and a navigation bar with the following options: Home, Download Messages, All Messages, Send Message, Board Status, and Log-out.

The breakdown of all pages and functions can be seen below:

<h3>1. Starting Menu Home</h3>
The landing homepage which is the first page seen by not logged-in users upon launching the application.

<h3>2. Registration</h3>
A page for creating new accounts which validates user inputs, and stores new users in the 'users' database table. Registration checks for existing users with the input email address to avoid duplication of users with the same email addresses.

<h3>3. Login</h3>
A page for entering credentials and logging in with existing accounts. Login checks the entered credentials for a match in the 'users' database. If successful, a new session is created, and the user is redirected to the application homepage.

<h3>4. Home</h3>
The landing homepage which users see after they log-in.

<h3>5. Download Messages</h3>
A page for downloading up to 30 valid messages from the M2M server with the GID (group identifier) XML tag present and set to 'AA'. Messages are stored in the database without duplication.

Message contents:
Temperature (-30 to 45 degrees Celsius)
Fan (Forward / Reverse)
Keypad (0 to 9, #, *)
Switch One (On / Off)
Switch Two (On / Off)
Switch Three (On / Off)
Switch Four (On / Off)

<h3>6. All Messages</h3>
A page for retrieving and viewing all messages that have already been downloaded and are stored in the database.

<h3>7. Send Message</h3>
A page for sending a valid message to the EE M2M server which can also be used to update the telemetry board's settings.

<h3>8. Board Status</h3>
A page for displaying the telemetry board's current status. The page retrieves the latest valid message from the database and presents the contents.

<h3>9. Log-out</h3>
Logging out ends the user's session and redirects them back to the starting menu where they can register or log-in again.
