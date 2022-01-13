# Account-Creation-API

## This API is designed to be used to create a user account and login.

In order to run this application, you need to have one of the PHP server installed: XAMPP

1. First, run the PHP server and extract this repository in the htdocs folder.
2. Now, create a new database on PHPMyAdmin and open the DB folder. You will find a SQL file to create a table that we will be using in order to store the user data.
3. Based on the host machine URL (http://localhost:8080), it's time to use the endpoints.
4. In order to create a new user account, you need to call this POST request: POST http://localhost:8080/accountcreation/api/register along with the username and password in the body request as JSON. You will recieve a response based on the POST request.
5. In order to login to the user account created, you need to call the POST request: POST http://localhost:8080/accountcreation/api/login along with the username and password in the body request as JSON. You will recieve a response based on the POST request.
6. If you use wrong request methods to call any of the above endpoints (GET instead of POST), you will receive an appropriate error.

## File Structure:
- api
  - login.php : This file handles the logic for the user login.
  - register.php : This file handles the logic for the new user account creation.
- DB
  - account_creation.sql : This file includes the SQL statement to create a table.
- classes
  - ApiMessage.php : This file handles the API messages that we return to the user.
  - Database.php : This file handles the database connection.

### Postman Collection file has been added to this repository which can be imported using Postman.
