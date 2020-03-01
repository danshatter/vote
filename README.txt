VOTING SYSTEM

/* This is my fifth personal project. It is a voting system for the Federal Republic of Nigeria which I have developed using PHP OOP concepts from scratch.  */

- Import the 'vote.sql' to get you started. This will create all the necessary tables for registration, voting, collation and the list of candidates for the different available positions. It comes with some dummy data so anyone could just get started immediately.

- Go to the 'credentials/secure.php' to fill in your database credentials.

- Go to the 'SITE_ROOT' and change it to whatever your enclosing folder is. If it is 'vote' like this, '/vote' is perfect.

- Go to the 'core/init.php' and edit the 'start_date' variable to when you feel election will get started. Make sure the 'start_date' variable is at an earlier date as the 'end_date' variable. If not, an error code of 500 will be produced. if the 'start_date' variable is later than the '$_SERVER['REQUEST_TIME']', an error code of 503 will be produced. When the '$_SERVER['REQUEST_TIME']' is later than the 'end_date' variable, voting closes and the election results will be out and anybody visiting the site will be redirected to the results page immediately.

- A user has to be registered to be able to cast their vote. A user can only vote once. There are both frontend and backend constraints to enforce this.

- For admin privileges, create a user and change the 'role' column in the 'users' table to '2'. When next you log in, you will be redirected to the admin page. Any user trying to access the admin area will be welcomed with a 403 error code. In the admin area, you can add a party, remove a party, add a candidate, remove a candidate and add a new admin user. Since admins are also users, they can also cast their votes.