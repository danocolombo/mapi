<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

// get all users
$app->get('/api/users', function(Request $request, Response $response){
	$sql = "SELECT * FROM users";
	
	try{
		//get db object
		$db = new db();
		// call connect
		$db = $db->connect();
		
		$stmt = $db->query($sql);	
		$users = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		//echo json_encode($users);
		return $response->withStatus(200)
		->withHeader('Content-Type','application/json')
		->write(json_encode($users));	
		
	}catch(PDOEXCEPTION $e){
		echo '{"error": {"text": '.$e->getMessage().'}';
	
	}
		
});

//get a single user
$app->get('/api/user/{id}', function(Request $request, Response $response){

	$id = $request->getAttribute('id');
	$sql = "SELECT * FROM users WHERE user_id = $id";
	
	try{
		//get db object
		$db = new db();
		// call connect
		$db = $db->connect();
		
		$stmt = $db->query($sql);	
		$user = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		return $response->withStatus(200)
		->withHeader('Content-Type','application/json')
		->write(json_encode($user));			
		
	}catch(PDOEXCEPTION $e){
		echo '{"error": {"text": '.$e->getMessage().'}';
	
	}
		
});

//add a user
$app->post('/api/user/add', function(Request $request, Response $response){
	$userName = $request->getParam('userName');
	$password = $request->getParam('password');
	$firstName = $request->getParam('firstName');
	$lastName = $request->getParam('lastName');
	$email = $request->getParam('email');
	$registered = $request->getParam('registered');
	$passwordReset = $request->getParam('passwordReset');
	$recoveryToken = $request->getParam('recoveryToken');
	$clientAccess = $request->getParam('clientAccess');
	
	
	$sql = "INSERT INTO meeter.users (user_login, user_password, user_firstname, user_surname, user_email, user_registered, password_reset, recovery_token, clientAccess) VALUES 
	(:userName, :password, :firstName, :lastName, :email, :registered, :passwordReset, :recoveryToken, :clientAccess)";
	try{
		//get db object
		$db = new db();
		// call connect
		$db = $db->connect();
		
		$stmt= $db->prepare($sql);
		$stmt->bindParam(':userName', $userName);
		$stmt->bindParam(':password', $password);
		$stmt->bindParam(':firstName', $firstName);
		$stmt->bindParam(':firstName', $firstName);
		$stmt->bindParam(':lastName', $lastName);
		$stmt->bindParam(':email', $email);
		$stmt->bindParam(':registered', $registered);
		$stmt->bindParam(':passwordReset', $passwordReset);	
		$stmt->bindParam(':recoveryToken', $$recoveryToken);
		$stmt->bindParam(':clientAccess', $clientAccess);
	
		
		$stmt->execute();
		
		echo '{"notice": {"text": "User Added"}';
		
		
	}catch(PDOEXCEPTION $e){
		echo '{"error": {"text": '.$e->getMessage().'}';
	
	}
		
});
//update a user
$app->put('/api/user/update/{id}', function(Request $request, Response $response){
	$id = $request->getAttribute('id');
	$userName = $request->getParam('userName');
	$password = $request->getParam('password');
	$firstName = $request->getParam('firstName');
	$lastName = $request->getParam('lastName');
	$email = $request->getParam('email');
	$registered = $request->getParam('registered');
	$passwordReset = $request->getParam('passwordReset');
	$recoveryToken = $request->getParam('recoveryToken');
	$clientAccess = $request->getParam('clientAccess');
	
	
	$sql = "UPDATE meeter.users SET
				user_login = :userName,
				user_password = :password,
				user_firstname = :firstName,
				user_surname = :lastName,
				user_email = :email,
				user_registered = :registered,
				password_reset = :passwordReset,
				recovery_token = :recoveryToken,
				clientAccess = :clientAccess
			WHERE user_id = $id";
	try{
		//get db object
		$db = new db();
		// call connect
		$db = $db->connect();
		
		$stmt= $db->prepare($sql);
		$stmt->bindParam(':userName', $userName);
		$stmt->bindParam(':password', $password);
		$stmt->bindParam(':firstName', $firstName);
		$stmt->bindParam(':firstName', $firstName);
		$stmt->bindParam(':lastName', $lastName);
		$stmt->bindParam(':email', $email);
		$stmt->bindParam(':registered', $registered);
		$stmt->bindParam(':passwordReset', $passwordReset);	
		$stmt->bindParam(':recoveryToken', $$recoveryToken);
		$stmt->bindParam(':clientAccess', $clientAccess);
	
		
		$stmt->execute();
		
		echo '{"notice": {"text": "User Updated"}';
		
		
	}catch(PDOEXCEPTION $e){
		echo '{"error": {"text": '.$e->getMessage().'}';
	
	}
		
});

//delete user
$app->delete('/api/user/delete/{id}', function(Request $request, Response $response){

	$id = $request->getAttribute('id');
	$sql = "DELETE FROM meeter.users WHERE user_id = $id";
	
	try{
		//get db object
		$db = new db();
		// call connect
		$db = $db->connect();
		
		$stmt = $db->prepare($sql);
		$stmt->execute();
		$db = null;	
		echo '{"notice": {"text": "User Deleted"}';
	}catch(PDOEXCEPTION $e){
		echo '{"error": {"text": '.$e->getMessage().'}';
	
	}
		
});

//get admins for client
$app->get('/api/client/getAdmins/{client}', function(Request $request, Response $response){

    	$userID = $_GET['uid'];

	$client = $request->getAttribute('client');
	$clientTable = $client . ".Meeter";
	switch($client){
	    case "ccc":
	        $sql = "SELECT Setting FROM ccc.Meeter WHERE Config = 'Admin'";
	        break;
	    case "cpv":
	        $sql = "SELECT Setting FROM cpv.Meeter WHERE Config = 'Admin'";
	        break;
	    case "wbc":
	        $sql = "SELECT Setting FROM wbc.Meeter WHERE Config = 'Admin'";
	        break;
	    default:
	        echo '{"error": {"text": <br/>NEED client<br/>'.$client.'}';
	        exit;
	}
	try{
		//get db object
		$db = new db();
		// call connect
		$db = $db->connect();
		$stmt = $db->prepare($sql);
		$stmt->execute(array($clientTable,'Admin'));
		$result = $stmt->fetch(PDO::FETCH_ASSOC);

		print_r($result);
		echo "<br/>Setting value = " . $result['Setting'] . " ----hmmmm\n";
		$admins = explode('#',$result['Setting']);
		$adminCheck = FALSE;
		foreach($admins as $admin){
			if($admin == $userID){
				$adminCheck = TRUE;
			}
		}
		if ($adminCheck == TRUE){
			echo "<br>The user IS and ADMIN" ;
		}else{
			echo "<br>The user IS NOT ADMIN";
		}
		exit;

		$db = null;
		return $response->withStatus(200)
		->withHeader('Content-Type','application/json')
		->write(json_encode($result));			
		
	}catch(PDOEXCEPTION $e){
		echo '{"error": {"text": '.$e->getMessage().'<br/>'.$sql.'<br/>'.$client.'}';
	
	}
		
});
//get admins for client
    $app->get('/api/user/isAdmin/{client}', function(Request $request, Response $response){
    $userID = $_GET['uid'];
//     echo "userID = " .  $userID;
    
    $client = $request->getAttribute('client');
    $clientTable = $client . ".Meeter";
    switch($client){
        case "ccc":
            $sql = "SELECT Setting FROM ccc.Meeter WHERE Config = 'Admin'";
            break;
        case "cpv":
            $sql = "SELECT Setting FROM cpv.Meeter WHERE Config = 'Admin'";
            break;
        case "wbc":
            $sql = "SELECT Setting FROM wbc.Meeter WHERE Config = 'Admin'";
            break;
        default:
            echo '{"error": {"text": <br/>NEED client<br/>'.$client.'}';
            exit;
    }
    try{
        //get db object
        $db = new db();
        // call connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute(array($clientTable,'Admin'));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $jsonFromDb = json_decode($result, true);
        $adminIDs = $jsonFromDb['Setting'];
        echo "Admin string for " . $client . " is >>" . $adminIDs . "\n\n";
	    echo "value: " . $result->Setting . " hmmm\n";
        exit;
        $db = null;
        return $response->withStatus(200)
        ->withHeader('Content-Type','application/json')
        ->write(json_encode($result));
        
    }catch(PDOEXCEPTION $e){
        echo '{"error": {"text": '.$e->getMessage().'<br/>'.$sql.'<br/>'.$client.'}';
        
    }
    
});



//get future meetings for client
$app->get('/api/meetings/getFuture/{client}', function(Request $request, Response $response){
    
    $client = $request->getAttribute('client');
    $tmpToday = date("Y-m-d");
    switch($client){
        case "ccc":
            $sql = "select m.ID meetingID, m.MtgDate meetingDate, m.MtgType meetingType, m.MtgTitle meetingTitle, p.fName meetingFacilitator, 
                w.fName worship, m.MtgAttendance meetingAttendance from ccc.meetings m, ccc.people p, ccc.people w where 
                m.MtgFac = p.ID and m.MtgWorship = w.ID AND m.MtgDate >= '" . $tmpToday . "' ORDER BY m.MtgDate ASC";
            break;
        case "cpv":
            $sql = "select m.ID meetingID, m.MtgDate meetingDate, m.MtgType meetingType, m.MtgTitle meetingTitle, p.fName meetingFacilitator,
                w.fName worship, m.MtgAttendance meetingAttendance from cpv.meetings m, cpv.people p, cpv.people w where
                m.MtgFac = p.ID and m.MtgWorship = w.ID AND m.MtgDate >= '" . $tmpToday . "' ORDER BY m.MtgDate ASC";
            break;
        case "wbc":
            $sql = "select m.ID meetingID, m.MtgDate meetingDate, m.MtgType meetingType, m.MtgTitle meetingTitle, p.fName meetingFacilitator,
                w.fName worship, m.MtgAttendance meetingAttendance from wbc.meetings m, wbc.people p, wbc.people w where
                m.MtgFac = p.ID and m.MtgWorship = w.ID AND m.MtgDate >= '" . $tmpToday . "' ORDER BY m.MtgDate ASC";
            break;
        default:
            echo '{"error": {"text": <br/>NEED client<br/>'.$client.'}';
            exit;
    }
    try{
        //get db object
        $db = new db();
        // call connect
        $db = $db->connect();
        $stmt = $db->query($sql);
        $meetings = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        $db = null;
        return $response->withStatus(200)
        ->withHeader('Content-Type','application/json')
        ->write(json_encode($meetings));
        
    }catch(PDOEXCEPTION $e){
        echo '{"error": {"text": '.$e->getMessage().'<br/>'.$sql.'<br/>'.$client.'}';
        
    }
    
});
    
//get past meetings for client
$app->get('/api/meetings/getHistory/{client}', function(Request $request, Response $response){
    
    $client = $request->getAttribute('client');
    $tmpToday = date("Y-m-d");
    switch($client){
        case "ccc":
            $sql = "select m.ID meetingID, m.MtgDate meetingDate, m.MtgType meetingType, m.MtgTitle meetingTitle, p.fName meetingFacilitator,
            w.fName worship, m.MtgAttendance meetingAttendance from ccc.meetings m, ccc.people p, ccc.people w where
            m.MtgFac = p.ID and m.MtgWorship = w.ID AND m.MtgDate <= '" . $tmpToday . "' ORDER BY m.MtgDate ASC";
            break;
        case "cpv":
            $sql = "select m.ID meetingID, m.MtgDate meetingDate, m.MtgType meetingType, m.MtgTitle meetingTitle, p.fName meetingFacilitator,
            w.fName worship, m.MtgAttendance meetingAttendance from cpv.meetings m, cpv.people p, cpv.people w where
            m.MtgFac = p.ID and m.MtgWorship = w.ID AND m.MtgDate <= '" . $tmpToday . "' ORDER BY m.MtgDate ASC";
            break;
        case "wbc":
            $sql = "select m.ID meetingID, m.MtgDate meetingDate, m.MtgType meetingType, m.MtgTitle meetingTitle, p.fName meetingFacilitator,
            w.fName worship, m.MtgAttendance meetingAttendance from wbc.meetings m, wbc.people p, wbc.people w where
            m.MtgFac = p.ID and m.MtgWorship = w.ID AND m.MtgDate <= '" . $tmpToday . "' ORDER BY m.MtgDate ASC";
            break;
        default:
            echo '{"error": {"text": <br/>NEED client<br/>'.$client.'}';
            exit;
    }
    try{
        //get db object
        $db = new db();
        // call connect
        $db = $db->connect();
        $stmt = $db->query($sql);
        $meetings = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        $db = null;
        return $response->withStatus(200)
        ->withHeader('Content-Type','application/json')
        ->write(json_encode($meetings));
        
    }catch(PDOEXCEPTION $e){
        echo '{"error": {"text": '.$e->getMessage().'<br/>'.$sql.'<br/>'.$client.'}';
        
    }
    
});
    //delete meeting
    $app->delete('/api/meeting/deleteAll/{client}', function(Request $request, Response $response){
        $client = $request->getAttribute('client');
        $id = $request->getParam('meetingID');
        if (strlen($id) < 1){
            // no meeting ID provided - exit
            $msg = 'meetingID required to perform delete. id:' . $id;
            return $response->withStatus(400)
            ->withHeader('Content-Type','application/json')
            ->write(json_encode($msg));
            
            exit;
        }
        switch($client){
            case "ccc":
                $sql = "DELETE FROM ccc.meetings WHERE ID = $id";
                $sql2 = "DELETE FROM ccc.groups WHERE MtgID = $id";
                break;
            case "cpv":
                $sql = "DELETE FROM cpv.meetings WHERE ID = $id";
                $sql2 = "DELETE FROM cpv.groups WHERE MtgID = $id";
                break;
            case "wbc":
                $sql = "DELETE FROM wbc.meetings WHERE ID = $id";
                $sql2 = "DELETE FROM wbc.groups WHERE MtgID = $id";
                break;
            default:
                echo '{"error": {"text": <br/>NEED client<br/>'.$client.'}';
                exit;
        }
        
        
        try{
            //get db object
            $db = new db();
            // call connect
            $db = $db->connect();
            
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $stmt = $db->prepare($sql2);
            $stmt->execute();
            
            $db = null;
            echo '{"notice": {"text": "All Meeting References Deleted"}';
        }catch(PDOEXCEPTION $e){
            echo '{"error": {"text": '.$e->getMessage().'}';
            
        }
        
    });
