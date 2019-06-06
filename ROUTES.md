#ROUTES.md
This is provided to outline supported routes and API functionality

GET ALL SYSTEM USERS
/api/users
	this returns all the fields in the meeter.user table which is all the users in the Meeter 2.0 system
	
GET SPECIFIC USER
/api/user/{id}
	this returns all the columns for the user relating to the id passed in.
	
ADD A USER
/api/user/add (with body information required)
	userName
	password
	firstName
	lastName
	email
	registered
	passwordRest
	recoveryToken
	dlientAccess
	
	
UPDATE USER
/api/user/update/{id} (with body informaiton required
	userName
	password
	firstName
	lastName
	email
	registered
	passwordRest
	recoveryToken
	dlientAccess
	
DELETE USER
/api/user/delete/{id}
	deletes the user from the meeter.users table with id
	
GET CLIENT ADMINS
/api/client/getAdmins/{client}
	this returns the Admin configuraiton informaton for the client from client.Meeter table