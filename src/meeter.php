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
//###################################
// get all the users for a client....
//
// http://rogueintel.org/mapi/public/index.php/api/client/getUsers/{client}
//
//###################################
// get all users
$app->get('/api/client/getUsers/{client}', function(Request $request, Response $response){
    $client = $request->getAttribute('client');
    if (!isset($client)){
        echo '{"error": {"text": <br/>NEED client<br/>'.$client.'}';
        exit;
    }

    $sql = "SELECT * FROM " . $client . ".users";
    
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
        

//###################################
// get all the people that can be admins for a client....
//
// http://rogueintel.org/mapi/public/index.php/api/client/getAdminLis/{client}
//
//###################################
$app->get('/api/client/getAdmins/{client}', function(Request $request, Response $response){
    //$userID = $_GET['uid'];

	$client = $request->getAttribute('client');
	if (!isset($client)){
	    echo '{"error": {"text": <br/>NEED client<br/>'.$client.'}';
	    exit;
	}
	$sql = "SELECT Setting FROM " . $client . ".Meeter WHERE Config = 'HostSet'";
	
	try{
		//get db object
		$db = new db();
		$db = $db->connect();
		$stmt = $db->prepare($sql);
		$stmt->execute(array($clientTable,'Admins'));
		$hostSet = $stmt->fetch(PDO::FETCH_ASSOC);
        $db = null;
        $ids = explode('|', $hostSet['Setting']);
		//now put ids into associative array...
		$theIds = $result[0];
		

		//now get all active people
		$theUrl = "http://rogueintel.org/mapi/public/index.php/api/client/getUsers/" . $client;
		$data = file_get_contents($theUrl); // put the contents of the file into a variable
		$users = json_decode($data); // decode the JSON feed

		foreach($users as $user){
		    $n = $user->user_firstname . " " . $user->user_surname;
		    $i = $user->user_id;
		    $systemUsers[$i] = $n;
		    
		}
		print_r($systemUsers);
		exit;
		//combine to create Ghost return value
		$ghost[$gid["Setting"]] = $glabel["Setting"]; 
		
		
		
		
		
		
//         var_dump($systemUsers);
        foreach($systemUsers as $u){
            $item[] = $u;
            echo "<br/>";
            print_r($item);
            echo "<br/>-------------------------------<br/>";
        }
		exit;
        $peeps[] = $data;
// 		echo "<br/><br/><br/>";
		
		foreach($peeps as $peep){
// 		    print_r($peep);
            echo $peep;
		    echo "<br/>*******************************<br/>";
		  
// 		    echo $peep['user_id'] . "<br/><br/><br/>";
		}
		
		
// 		$peeps2[] = $systemUsers;
//         print_r($data);
//         echo "<br/><br/>";
//         print_r($peeps1);
//         echo "<br/>##################<br/>";
//         print_r($systemUsers);
//         echo "<br/><br/>";
//         print_r($peeps2);
		exit;
		
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
    //--------------------------------------------------------------------------
    // this is used to check if the user is an admin for the client
    //  http://100.25.128.0/mapi/public/index.php/api/user/isAdmin/ccc?uid=7
    //
    // returns either JSON true or false
    //  ["admin","true"]
    //  or
    //  ["admin","false"]
    //---------------------------------------------------------------------------   
    $userID = $_GET['uid'];
    
    $client = $request->getAttribute('client');
    $clientTable = $client . ".Meeter";
    switch($client){
        case "ccc":
            $sql = "SELECT Setting FROM ccc.Meeter WHERE Config = 'Admins'";
            break;
        case "cpv":
            $sql = "SELECT Setting FROM cpv.Meeter WHERE Config = 'Admins'";
            break;
        case "wbc":
            $sql = "SELECT Setting FROM wbc.Meeter WHERE Config = 'Admins'";
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
        $stmt->execute(array($clientTable,'Admins'));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $admins = explode('|',$result['Setting']);
        $adminCheck = FALSE;
        foreach($admins as $admin){
        
        }
        if ($adminCheck == TRUE){
            $meeterCheck->admin = "true";
        }else{
            $meeterCheck->admin = "false";
        }
        
        $db = null;
        return $response->withStatus(200)
        ->withHeader('Content-Type','application/json')
        ->write(json_encode($meeterCheck));
        
    }catch(PDOEXCEPTION $e){
        echo '{"error": {"text": '.$e->getMessage().'<br/>'.$sql.'<br/>'.$client.'}';
        
    }
});

//######################################################################################
// ADD MEETING TO APPROPRIATE CLIENT TABLE
// call it....  http://rogueintel.org/mapi/public/index.php/api/meeting/create/{client}
//######################################################################################
$app->post('/api/meeting/create/{client}', function(Request $request, Response $response){
    
    $client = $request->getAttribute('client');

    $tmpDate = $request->getParam('mtgDate');
    $mtgDate = date("Y-m-d", strtotime($tmpDate));
    $mtgType = $request->getParam('mtgType');
    $mtgTitle = $request->getParam('mtgTitle');
    $mtgFac = $request->getParam('mtgHost');
    $mtgAttendance = $request->getParam('mtgAttendance');
    
    $donations = $request->getParam('donations');
    $worshipFac = $request->getParam('worshipFac');
    $audioVisualFac = $request->getParam('audioVisualFac');
    $setupFac = $request->getParam('setupFac');
    $transportationFac = $request->getParam('transportationFac');
    
    $greeter1Fac = $request->getParam('greeter1Fac');
    $greeter2Fac = $request->getParam('greeter2Fac');
    $resourcesFac = $request->getParam('resourcesFac');
    $meal = $request->getParam('menu');
    $mealCnt = $request->getParam('mealCnt');
    
    $mealFac = $request->getParam('mealFac');
    $reader1Fac = $request->getParam('reader1Fac');
    $reader2Fac = $request->getParam('reader2Fac');
    $announcementsFac = $request->getParam('announcementsFac');
    $teachingFac = $request->getParam('teachingFac');
    
    $chips1Fac = $request->getParam('chips1Fac');
    $chips2Fac = $request->getParam('chips2Fac');
    $serenityFac = $request->getParam('serenityFac');
    $newcomers1Fac = $request->getParam('newcomers1Fac');
    $newcomers2Fac = $request->getParam('newcomers2Fac');
    
    $nurseryCnt = $request->getParam('nurseryCnt');
    $nurseryFac = $request->getParam('nurseryFac');
    $childrenCnt = $request->getParam('childrenCnt');
    $childrenFac = $request->getParam('childrenFac');
    $youthCnt = $request->getParam('youthCnt');
    
    $youthFac = $request->getParam('youthFac');
    $cafeFac = $request->getParam('cafeFac');
    $tearDownFac = $request->getParam('tearDownFac');
    $securityFac = $request->getParam('securityFac');
    $notes = $request->getParam('mtgNotes');
    if(!isset($notes)){
        $notes = "";
    }
    
    
    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    $sql = "INSERT INTO ";
    $sql .= $client;
    $sql .= ".meetings (MtgDate, MtgType, MtgTitle, MtgFac, MtgWorship,";
    $sql .= " MtgAttendance, Meal, MealCnt, NurseryCnt, ChildrenCnt,";
    $sql .= " YouthCnt, MtgNotes, Donations, Newcomers1Fac, Newcomers2Fac,";
    $sql .= " Reader1Fac, Reader2Fac, NurseryFac, ChildrenFac, YouthFac,";
    $sql .= " MealFac, CafeFac, TransportationFac, SetupFac, TearDownFac,";
    $sql .= " Greeter1Fac, Greeter2Fac, Chips1Fac, Chips2Fac, ResourcesFac,";
    $sql .= " TeachingFac, SerenityFac, AudioVisualFac, AnnouncementsFac, SecurityFac)";
    
    $sql .= " VALUES (:MtgDate, :MtgType, :MtgTitle, :MtgFac, :MtgWorship,";
    $sql .= " :MtgAttendance, :Meal, :MealCnt, :NurseryCnt, :ChildrenCnt,";
    $sql .= " :YouthCnt, :MtgNotes, :Donations, :Newcomers1Fac, :Newcomers2Fac,";
    $sql .= " :Reader1Fac, :Reader2Fac, :NurseryFac, :ChildrenFac, :YouthFac,";
    $sql .= " :MealFac, :CafeFac, :TransportationFac, :SetupFac, :TearDownFac,";
    $sql .= " :Greeter1Fac, :Greeter2Fac, :Chips1Fac, :Chips2Fac, :ResourcesFac,";
    $sql .= " :TeachingFac, :SerenityFac, :AudioVisualFac, :AnnouncementsFac, :SecurityFac)";
    
    try{
        //get db object
        $db = new db();
        // call connect
        $db = $db->connect();
        
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':MtgDate', $mtgDate);
        $stmt->bindParam(':MtgType', $mtgType);
        $stmt->bindParam(':MtgTitle', $mtgTitle);
        $stmt->bindParam(':MtgFac', $mtgFac);
        $stmt->bindParam(':MtgWorship', $worshipFac);
        
        $stmt->bindParam(':MtgAttendance', $mtgAttendance);
        $stmt->bindParam(':Meal', $meal);
        $stmt->bindParam(':MealCnt', $mealCnt);
        $stmt->bindParam(':NurseryCnt', $nurseryCnt);
        $stmt->bindParam(':ChildrenCnt', $childrenCnt);
        
        $stmt->bindParam(':YouthCnt', $youthCnt);
        $stmt->bindParam(':MtgNotes', $notes);
        $stmt->bindParam(':Donations', $donations);
        $stmt->bindParam(':Newcomers1Fac', $newcomers1Fac);
        $stmt->bindParam(':Newcomers2Fac', $newcomers2Fac);
        
        $stmt->bindParam(':Reader1Fac', $reader1Fac);
        $stmt->bindParam(':Reader2Fac', $reader2Fac);
        $stmt->bindParam(':NurseryFac', $nurseryFac);
        $stmt->bindParam(':ChildrenFac', $childrenFac);
        $stmt->bindParam(':YouthFac', $youthFac);
        
        $stmt->bindParam(':MealFac', $mealFac);
        $stmt->bindParam(':CafeFac', $cafeFac);
        $stmt->bindParam(':TransportationFac', $transportationFac);
        $stmt->bindParam(':SetupFac', $setupFac);
        $stmt->bindParam(':TearDownFac', $tearDownFac);
        
        $stmt->bindParam(':Greeter1Fac', $greeter1Fac);
        $stmt->bindParam(':Greeter2Fac', $greeter2Fac);
        $stmt->bindParam(':Chips1Fac', $chips1Fac);
        $stmt->bindParam(':Chips2Fac', $chips2Fac);
        $stmt->bindParam(':ResourcesFac', $resourcesFac);
        
        $stmt->bindParam(':TeachingFac', $teachingFac);
        $stmt->bindParam(':SerenityFac', $serenityFac);
        $stmt->bindParam(':AudioVisualFac', $audioVisualFac);
        $stmt->bindParam(':AnnouncementsFac', $announcementsFac);
        $stmt->bindParam(':SecurityFac', $securityFac);
        
        $stmt->execute();
        echo '{"notice": {"text": "User Added"}';
    }catch(PDOEXCEPTION $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
        
    }
    
    
});
    //######################################################################################
    // UPDATE MEETING TO APPROPRIATE CLIENT TABLE
    // call it....  http://rogueintel.org/mapi/public/index.php/api/meeting/udpate/{client}&MID=248
    //######################################################################################
    $app->post('/api/meeting/update/{client}', function(Request $request, Response $response){
        
        $client = $request->getAttribute('client');
        $mid = $request->getParam('MID');
        
        $tmpDate = $request->getParam('mtgDate');
        $mtgDate = date("Y-m-d", strtotime($tmpDate));

        $mtgType = $request->getParam('mtgType');
        $mtgTitle = $request->getParam('mtgTitle');
        
        if(!is_numeric($mid)){
            //echo '{"error": {"text": '.$e->getMessage().'}';
            return $response->withStatus(400)
                ->withHeader('Content-Type', 'text/html')
                ->write('MAPI: INVALID REQUEST [ID]');
            
        }
        if((strlen($mtgType) < 6) or 
            (strlen($mtgTitle) < 4)){
            //echo '{"error": {"text": '.$e->getMessage().'}';
            return $response->withStatus(400)
            ->withHeader('Content-Type', 'text/html')
            ->write('MAPI: INVALID REQUEST');
        }
        

        $mtgFac = $request->getParam('mtgHost');
        $mtgAttendance = $request->getParam('mtgAttendance');
        
        $donations = $request->getParam('donations');
        $worshipFac = $request->getParam('worshipFac');
        $audioVisualFac = $request->getParam('audioVisualFac');
        $setupFac = $request->getParam('setupFac');
        $transportationFac = $request->getParam('transportationFac');
        
        $greeter1Fac = $request->getParam('greeter1Fac');
        $greeter2Fac = $request->getParam('greeter2Fac');
        $resourcesFac = $request->getParam('resourcesFac');
        $meal = $request->getParam('menu');
        $mealCnt = $request->getParam('mealCnt');
        
        $mealFac = $request->getParam('mealFac');
        $reader1Fac = $request->getParam('reader1Fac');
        $reader2Fac = $request->getParam('reader2Fac');
        $announcementsFac = $request->getParam('announcementsFac');
        $teachingFac = $request->getParam('teachingFac');
        
        $chips1Fac = $request->getParam('chips1Fac');
        $chips2Fac = $request->getParam('chips2Fac');
        $serenityFac = $request->getParam('serenityFac');
        $newcomers1Fac = $request->getParam('newcomers1Fac');
        $newcomers2Fac = $request->getParam('newcomers2Fac');
        
        $nurseryCnt = $request->getParam('nurseryCnt');
        $nurseryFac = $request->getParam('nurseryFac');
        $childrenCnt = $request->getParam('childrenCnt');
        $childrenFac = $request->getParam('childrenFac');
        $youthCnt = $request->getParam('youthCnt');
        
        $youthFac = $request->getParam('youthFac');
        $cafeFac = $request->getParam('cafeFac');
        $tearDownFac = $request->getParam('tearDownFac');
        $securityFac = $request->getParam('securityFac');
        $notes = $request->getParam('mtgNotes');
        if(!isset($notes)){
            $notes = "";
        }
        
        
        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        $sql = "UPDATE ";
        $sql .= $client;
        $sql .= ".meetings SET MtgDate = :MtgDate, MtgType = :MtgType, MtgTitle = :MtgTitle, MtgFac = :MtgFac, MtgWorship = :MtgWorship,";
        $sql .= " MtgAttendance = :MtgAttendance, Meal = :Meal, MealCnt = :MealCnt, NurseryCnt = :NurseryCnt, ChildrenCnt = :ChildrenCnt,";
        $sql .= " YouthCnt = :YouthCnt, MtgNotes = :MtgNotes, Donations = :Donations, Newcomers1Fac = :Newcomers1Fac, Newcomers2Fac = :Newcomers2Fac,";
        $sql .= " Reader1Fac = :Reader1Fac, Reader2Fac = :Reader2Fac, NurseryFac = :NurseryFac, ChildrenFac = :ChildrenFac, YouthFac = :YouthFac,";
        $sql .= " MealFac = :MealFac, CafeFac = :CafeFac, TransportationFac = :TransportationFac, SetupFac = :SetupFac, TearDownFac = :TearDownFac,";
        $sql .= " Greeter1Fac = :Greeter1Fac, Greeter2Fac = :Greeter2Fac, Chips1Fac = :Chips1Fac, Chips2Fac = :Chips2Fac, ResourcesFac = :ResourcesFac,";
        $sql .= " TeachingFac = :TeachingFac, SerenityFac = :SerenityFac, AudioVisualFac = :AudioVisualFac, AnnouncementsFac = :AnnouncementsFac, SecurityFac = :SecurityFac";
        $sql .= " WHERE ID = :MID";

        try{
            //get db object
            $db = new db();
            // call connect
            $db = $db->connect();
            
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':MID', $mid);
            $stmt->bindParam(':MtgDate', $mtgDate);
            $stmt->bindParam(':MtgType', $mtgType);
            $stmt->bindParam(':MtgTitle', $mtgTitle);
            $stmt->bindParam(':MtgFac', $mtgFac);
            $stmt->bindParam(':MtgWorship', $worshipFac);
            
            $stmt->bindParam(':MtgAttendance', $mtgAttendance);
            $stmt->bindParam(':Meal', $meal);
            $stmt->bindParam(':MealCnt', $mealCnt);
            $stmt->bindParam(':NurseryCnt', $nurseryCnt);
            $stmt->bindParam(':ChildrenCnt', $childrenCnt);
            
            $stmt->bindParam(':YouthCnt', $youthCnt);
            $stmt->bindParam(':MtgNotes', $notes);
            $stmt->bindParam(':Donations', $donations);
            $stmt->bindParam(':Newcomers1Fac', $newcomers1Fac);
            $stmt->bindParam(':Newcomers2Fac', $newcomers2Fac);
            
            $stmt->bindParam(':Reader1Fac', $reader1Fac);
            $stmt->bindParam(':Reader2Fac', $reader2Fac);
            $stmt->bindParam(':NurseryFac', $nurseryFac);
            $stmt->bindParam(':ChildrenFac', $childrenFac);
            $stmt->bindParam(':YouthFac', $youthFac);
            
            $stmt->bindParam(':MealFac', $mealFac);
            $stmt->bindParam(':CafeFac', $cafeFac);
            $stmt->bindParam(':TransportationFac', $transportationFac);
            $stmt->bindParam(':SetupFac', $setupFac);
            $stmt->bindParam(':TearDownFac', $tearDownFac);
            
            $stmt->bindParam(':Greeter1Fac', $greeter1Fac);
            $stmt->bindParam(':Greeter2Fac', $greeter2Fac);
            $stmt->bindParam(':Chips1Fac', $chips1Fac);
            $stmt->bindParam(':Chips2Fac', $chips2Fac);
            $stmt->bindParam(':ResourcesFac', $resourcesFac);
            
            $stmt->bindParam(':TeachingFac', $teachingFac);
            $stmt->bindParam(':SerenityFac', $serenityFac);
            $stmt->bindParam(':AudioVisualFac', $audioVisualFac);
            $stmt->bindParam(':AnnouncementsFac', $announcementsFac);
            $stmt->bindParam(':SecurityFac', $securityFac);
            
            $stmt->execute();
//             echo '{"notice": {"text": "User Added"}';
            return $response->withStatus(200)
            ->withHeader('Content-Type', 'text/html')
            ->write('MEETING UPDATED');
            exit;
            
        }catch(PDOEXCEPTION $e){
            return $response->withStatus(500)
            ->withHeader('Content-Type', 'text/html')
            ->write('DATABASE UPDATE FAILED: ' . $e->getMessage());
            exit;
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
    //###########################
    // GET SYSTEM CONFIG FOR CLIENT
    //
    //  http://rogueintel.org/mapi/public/index.php/api/client/getConfig/wbc
    //
    // ###########################
    $app->get('/api/client/getConfig/{client}', function(Request $request, Response $response){
        $client = $request->getAttribute('client');
        switch($client){
            case "ccc":
                $sql = "SELECT Setting FROM ccc.Meeter WHERE Config = 'AOS'";
                break;
            case "cpv":
                $sql = "SELECT Setting FROM cpv.Meeter WHERE Config = 'AOS'";
                break;
            case "wbc":
                $sql = "SELECT Setting FROM wbc.Meeter WHERE Config = 'AOS'";
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
            $config = $stmt->fetchAll(PDO::FETCH_BOTH);
            
            $zero = $config[0];
            $values = explode("|", $zero[0]);
            $db = null;
            return $response->withStatus(200)
            ->withHeader('Content-Type','application/json')
            ->write(json_encode($values));
            
        }catch(PDOEXCEPTION $e){
            echo '{"error": {"text": '.$e->getMessage().'<br/>'.$sql.'<br/>'.$client.'}';
            
        }
    });
//###################################
// get meeting for a client
//
//  http://rogueintel.org/mapi/public/index.php/api/client/getMeeting/wbc?mid=23
//
//###################################
$app->get('/api/client/getMeeting/{client}', function(Request $request, Response $response){
    $meetingID = $_GET['mid'];
//     echo "meetingID: $meetingID<br>";
//     exit;
    if(isset($meetingID)){
        if (strlen($meetingID)<1){
            echo '{"error": {"text": <br/>NEED meeting number<br/>'.$client.'}';
            exit;
        }
    }else{
        echo '{"error": {"text": <br/>NEED meeting number<br/>'.$client.'}';
        exit;
    }
    $client = $request->getAttribute('client');
    switch($client){
        case "ccc":
            $sql = "SELECT * FROM ccc.meetings WHERE ID = $meetingID";
            break;
        case "cpv":
            $sql = "SELECT * FROM cpv.meetings WHERE ID = $meetingID";
            break;
        case "wbc":
            $sql = "SELECT * FROM wbc.meetings WHERE ID = $meetingID";
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
        $meeting = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return $response->withStatus(200)
        ->withHeader('Content-Type','application/json')
        ->write(json_encode($meeting));
    }catch(PDOEXCEPTION $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});
//###################################
// get Meeter info
//
//  http://rogueintel.org/mapi/public/index.php/api/client/getMeeterInfo/{client}
//
//      this will return the Meeter table for the client
//
//
//###################################
$app->get('/api/client/getMeeterInfo/{client}', function(Request $request, Response $response){
    $client = $request->getAttribute('client');
    // first thing is to get the Nobody value
    switch($client){
        case "ccc":
            $sql = "SELECT * FROM ccc.Meeter";
            break;
        case "cpv":
            $sql = "SELECT * FROM cpv.Meeter";
            break;
        case "wbc":
            $sql = "SELECT * FROM wbc.Meeter";
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
        $configs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;
        return $response->withStatus(200)
        ->withHeader('Content-Type','application/json')
        ->write(json_encode($configs));
    }catch(PDOEXCEPTION $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});
//###################################
// get volunteer commits
//
//  http://rogueintel.org/mapi/public/index.php/api/client/getCommits/{client}
//
//
//###################################
$app->get('/api/client/getCommits/{client}', function(Request $request, Response $response){
    $client = $request->getAttribute('client');
    
    //delete all the records in the Commits table
    
    //get all active people
    
    // parse AOS value and store in Commits table
    
    // get Commits sorted by Category
    
    // return Commits value
    // first thing is to get the Nobody value
    switch($client){
        case "ccc":
            $sql = "SELECT * FROM ccc.Commits ORDER BY Category, LName, FName";
            break;
        case "cpv":
            $sql = "SELECT * FROM cpv.Commits ORDER BY Category, LName, FName";
            break;
        case "wbc":
            $sql = "SELECT * FROM wbc.Commits ORDER BY Category, LName, FName";
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
        $commits = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;
        return $response->withStatus(200)
        ->withHeader('Content-Type','application/json')
        ->write(json_encode($commits));
    }catch(PDOEXCEPTION $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
//    
//     PPPP  EEEE  OOO  PPPP  L    EEEE
//     P   P E    O   O P   P L    E
//     PPPP  EEE  O   O PPPP  L    EEE
//     P     E    O   O P     L    E
//     P     EEEE  OOO  P     LLLL EEEE 
//
//$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
$app->get('/api/people/getAll/{client}', function(Request $request, Response $response){
    $client = $request->getAttribute('client');
    
    $sql = "SELECT * FROM :client ORDER BY :lname, FName";

    $sql = "SELECT * FROM ";
    $sql .= $client;
    $sql .= ".people ORDER BY LName, FName";

    
    try{
        //get db object
        $db = new db();
        // call connect
        $db = $db->connect();
        $stmt = $db->query($sql);
        $commits = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;
        return $response->withStatus(200)
        ->withHeader('Content-Type','application/json')
        ->write(json_encode($commits));
        
    }catch(PDOEXCEPTION $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
        
    }
        
});
$app->get('/api/people/get/{client}', function(Request $request, Response $response){
    $client = $request->getAttribute('client');
    $pid = $request->getParam('PID');
    
    $sql = "SELECT * FROM ";
    $sql .= $client;
    $sql .= ".people WHERE ID = :PID ORDER BY LName, FName";
    
    try{
        //get db object
        $db = new db();
        // call connect
        $db = $db->connect();
        
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':PID', $pid);
        
        $stmt->execute();
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        //             echo '{"notice": {"text": "User Added"}';
        return $response->withStatus(200)
        ->withHeader('Content-Type', 'text/html')
        ->write(json_encode($results));
        exit;
        
    }catch(PDOEXCEPTION $e){
        return $response->withStatus(500)
        ->withHeader('Content-Type', 'text/html')
        ->write('CANNOT GET PEOPLE REQUEST: ' . $e->getMessage());
        exit;
    }
    
});

//###################################
// get people table value
//
//  http://rogueintel.org/mapi/public/index.php/api/client/getPeople/{client}
//  http://rogueintel.org/mapi/public/index.php/api/client/getPeople/{client}?filer=active
//      this will return the people table, with option to filter on active
//
//
//###################################
$app->get('/api/client/getPeople/{client}', function(Request $request, Response $response){
    // optional filter is provided to create subset of people
    $filter = $_GET['filter'];
    $client = $request->getAttribute('client');
    
   
    // first thing is to get the Nobody value
    switch($client){
        case "ccc":
            $sql = "SELECT * FROM ccc.people";
            break;
        case "cpv":
            $sql = "SELECT * FROM cpv.people";
            break;
        case "uat":
            $sql = "SELECT * FROM uat.people";
            break;
        case "wbc":
            $sql = "SELECT * FROM wbc.people";
            break;
        default:
            echo '{"error": {"text": <br/>NEED client<br/>'.$client.'}';
            exit;
    }
    if (isset($filter)){
        switch ($filter){
            case "active":
                $sql .= " WHERE Active = 1";
                break;
        }
    }
    $sql .= " ORDER BY ID";
    try{
        //get db object
        $db = new db();
        // call connect
        $db = $db->connect();
        $stmt = $db->query($sql);
        $people = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;
        return $response->withStatus(200)
        ->withHeader('Content-Type','application/json')
        ->write(json_encode($people));
    }catch(PDOEXCEPTION $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});
//###################################
// get a person from people table
//
//  http://rogueintel.org/mapi/public/index.php/api/client/getPerson/{client}?id=#
//
//###################################
$app->get('/api/client/getPerson/{client}', function(Request $request, Response $response){
    // optional filter is provided to create subset of people
    $id = $_GET['id'];
    if(!isset($id)){
        echo '{"error": {"text": <br/>NEED ID<br/>'.'}';
        exit;
    }
    $client = $request->getAttribute('client');
    
    
    // first thing is to get the Nobody value
    switch($client){
        case "ccc":
            $sql = "SELECT * FROM ccc.people WHERE ID = $id";
            break;
        case "cpv":
            $sql = "SELECT * FROM cpv.people WHERE ID = $id";
            break;
        case "wbc":
            $sql = "SELECT * FROM wbc.people WHERE ID = $id";
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
        $people = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;
        return $response->withStatus(200)
        ->withHeader('Content-Type','application/json')
        ->write(json_encode($people));
    }catch(PDOEXCEPTION $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});
    //###################################
    // get the potential admins for a client
    //
    // http://rogueintel.org/mapi/public/index.php/api/client/getAdminCandidates/{client}
    //
    //###################################
    $app->get('/api/client/getAdminCandidates/{client}', function(Request $request, Response $response){
        $client = $request->getAttribute('client');
        
        
        // first thing is to get the Nobody value
        $sql = "SELECT ID, FName, LName FROM $client.people WHERE Active = 1 AND length(LName)>0 ORDER BY FName";
//         switch($client){
//             case "ccc":
//                 $sql = "SELECT * FROM ccc.people WHERE ID = $id";
//                 break;
//             case "cpv":
//                 $sql = "SELECT * FROM cpv.people WHERE ID = $id";
//                 break;
//             case "uat":
//                 $sql = "SELECT * FROM uat.people WHERE ID = $id";
//                 break;
//             case "wbc":
//                 $sql = "SELECT * FROM wbc.people WHERE ID = $id";
//                 break;
//             default:
//                 echo '{"error": {"text": <br/>NEED client<br/>'.$client.'}';
//                 exit;
//         }
        try{
            //get db object
            $db = new db();
            // call connect
            $db = $db->connect();
            $stmt = $db->query($sql);
            $people = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $db = null;
            return $response->withStatus(200)
            ->withHeader('Content-Type','application/json')
            ->write(json_encode($people));
        }catch(PDOEXCEPTION $e){
            echo '{"error": {"text": '.$e->getMessage().'}';
        }
    });
//################################################
// get the lable for the client referncing a ghost (nobody)
//
// http://rogueintel.org/mapi/public/index.php/api/client/getGhost/{client}
//
//################################################
$app->get('/api/client/getGhost/{client}', function(Request $request, Response $response){
    $client = $request->getAttribute('client');
    $sql1 = "SELECT Setting FROM " . $client . ".Meeter WHERE Config = \"GhostID\"";
    $sql2 = "SELECT Setting FROM " . $client . ".Meeter WHERE Config = \"GhostLabel\"";
    
    try{
        //get db object
        $db = new db();
        
        $db = $db->connect();
        // get the Ghost ID
        $stmt = $db->query($sql1);
        $tmp1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $gid = $tmp1[0];
        // get the Ghost label
        $stmt = $db->query($sql2);
        $tmp2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $glabel = $tmp2[0];
        $db = null;
        
        //combine to create Ghost return value
        $ghost[$gid["Setting"]] = $glabel["Setting"]; 
        
        return $response->withStatus(200)
        ->withHeader('Content-Type','application/json')
        ->write(json_encode($ghost));
    }catch(PDOEXCEPTION $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});
//################################################
// get the lable for the client referncing a nonPersonWorshipInfo (nobody)
//
// http://rogueintel.org/mapi/public/index.php/api/client/getNonPersonWorshipInfo/{client}
//
//################################################
$app->get('/api/client/getNonPersonWorshipInfo/{client}', function(Request $request, Response $response){
    $client = $request->getAttribute('client');
    $sql1 = "SELECT Setting FROM " . $client . ".Meeter WHERE Config = \"NonPersonWorshipID\"";
    $sql2 = "SELECT Setting FROM " . $client . ".Meeter WHERE Config = \"NonPersonWorshipLabel\"";
    
    try{
        //get db object
        $db = new db();
        
        $db = $db->connect();
        // get the Ghost ID
        $stmt = $db->query($sql1);
        $tmp1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $gid = $tmp1[0];
        // get the Ghost label
        $stmt = $db->query($sql2);
        $tmp2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $glabel = $tmp2[0];
        $db = null;
        
        //combine to create Ghost return value
        $ghost[$gid["Setting"]] = $glabel["Setting"];
        
        return $response->withStatus(200)
        ->withHeader('Content-Type','application/json')
        ->write(json_encode($ghost));
    }catch(PDOEXCEPTION $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//###################################
// get id of meeting for date/type/title combo
//
// http://rogueintel.org/mapi/public/index.php/api/client/getIdForDTT/{client}
//
//###################################
$app->get('/api/client/getIdForDTT/{client}', function(Request $request, Response $response){
    $client = $request->getAttribute('client');
    //-----------------------------------------
    // this checks if there is a meeting entry for the date, type and title for the client
    //-------------------------------------------------------------------------------------
//     $mtgDate = $request->getParam('mtgDate');
//     $mtgType = $request->getParam('mtgType');
//     $mtgTitle = $request->getParam('mtgTitle');
    
    $mtgDate = $request->getQueryParam('mtgDate');
    $mtgType = $request->getQueryParam('mtgType');
    $mtgTitle = $request->getQueryParam('mtgTitle');

    
    if (!isset($mtgDate)){
        echo '{"error": {"text": <br/>NEED date<br/>'.$mtgDate.'}';
        exit;
    }
    if (!isset($mtgType)){
        echo '{"error": {"text": <br/>NEED type<br/>'.$mtgType.'}';
        exit;
    }
    if (!isset($mtgTitle)){
        echo '{"error": {"text": <br/>NEED title<br/>'.$mtgTitle.'}';
        exit;
    }
    
    $sql = "SELECT ID FROM " . $client . ".meetings";
    $sql .= " WHERE MtgDate = '" . $mtgDate . "' AND";
    $sql .= " MtgType = '" . $mtgType . "' AND";
    $sql .= " MtgTitle = '" . $mtgTitle . "'";
    
    try{
        //get db object
        $db = new db();
        // call connect
        $db = $db->connect();
        $stmt = $db->query($sql);
        $id = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        return $response->withStatus(200)
        ->withHeader('Content-Type','application/json')
        ->write(json_encode($id));
    }catch(PDOEXCEPTION $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});


//###################################
// get all the people that can be admins for a client....
//
// http://rogueintel.org/mapi/public/index.php/api/client/getAdminList/{client}
//
//###################################
$app->get('/api/client/getAdminList/{client}', function(Request $request, Response $response){
    $client = $request->getAttribute('client');
    
    
    // first thing is to get the Nobody value
    $theUrl = "http://rogueintel.org/mapi/public/index.php/api/client/getAdmins/" . $client;
    $data = file_get_contents($theUrl); // put the contents of the file into a variable
    $ghostLabel = json_decode($data); // decode the JSON feed
    
    print_r($data);
    exit;
    
    $sql = "SELECT ID, FName, LName FROM $client.people WHERE Active = 1 AND length(LName)>0 ORDER BY FName";
   
    try{
        //get db object
        $db = new db();
        // call connect
        $db = $db->connect();
        $stmt = $db->query($sql);
        $people = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $db = null;
        return $response->withStatus(200)
        ->withHeader('Content-Type','application/json')
        ->write(json_encode($people));
    }catch(PDOEXCEPTION $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//===========================================================================================
//	get list of meetings active (today and into future
//
//  ENDPOINT
// 	http://rogueintel.org/mapi/public/index.php/api/client/getAcitveMeetings/{client}
//
//===========================================================================================
$app->get('/api/client/getActiveMeetingList/{client}', function(Request $request, Response $response){
//$app->get('/api/client/getActiveMeetings/{client}', function(Request $request, Response $response){
	// optional filter is provided to create subset of people
	//$filter = $_GET['filter'];
	$client = $request->getAttribute('client');
	
	//this is what we want to create.....
	//sql = "SELECT m.ID, m.MtgDate, m.MtgType, m.MtgTitle, fac.fName, wor.fName, m.MtgAttendance FROM ccc.meetings m"
	//  + " INNER JOIN ccc.people fac ON m.MtgFac = fac.ID"
	//  + " INNER JOIN ccc.people wor ON m.MtgWorship = wor.ID"
	//  + " WHERE m.MtgDate >= '2019-08-01'";
	
	$sql = "SELECT m.ID, m.MtgDate, m.MtgType, m.MtgTitle, fac.fName, wor.fName, m.MtgAttendance FROM " . $client . ".meetings m";
	$sql .= " INNER JOIN " . $client . ".people fac ON m.MtgFac = fac.ID";
	$sql .= " INNER JOIN " . $client . ".people wor ON m.MtgWorship = wor.ID"; 
	$sql .= " WHERE m.MtgDate >= #2019-08-01#";
    echo $sql;
    exit;
	try{
		//get db object
	       $db = new db();
	       // call connect
	       $db = $db->connect();
	       $stmt = $db->query($sql);
	       $meetings = $stmt->fetchAll(PDO::FETCH_ASSOC);
	       $db = null;
	       return $response->withStatus(200)
		->withHeader('Content-Type','application/json')
		->write(json_encode($meetings));
	}catch(PDOEXCEPTION $e){
		echo '{"error": {"text": '.$e->getMessage().'}';
	}
});
