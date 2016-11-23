<?php
  header('Accept: application/json');
  header("Content-type: application/json");
  require_once __DIR__ . "/dataLayer.php";

  $action = $_POST["action"];

    switch ($action) {
    case "LOGINSTUDENT":
      loginStudent();
      break;
    case "LOGINPROF":
      loginProf();
      break;
    case "LOGOUT";
    	logout();
    	break;
    case "RETRIEVE_COOKIE":
      retrieveCookie();
      break;
    case "CREATE_COOKIE":
      createCookie();
      break;
    case "RETRIEVE_SESSION":
      retrieveSession();
      break;
    case "REGISTER_USER":
      registerUser();
      break;
    case "LOAD_ACAD":
      loadAcad();
      break;
    case "LOAD_ADMIN":
      loadAdmin();
      break;
    case "ADDTOPIC":
      add_topic();
      break;
    case "LOAD_LAST_ACAD":
      lastAcad();
      break;
    case "LOAD_LAST_ADMIN":
      lastAdmin();
      break;
    case "ADDADMINTOPIC":
      add_admintopic();
      break;
    case "LOAD_ADMINTHREAD":
      load_adminthread();
      break;
  }

  function loginStudent(){
  	$username = $_POST["username"];

  	$result = loginStudentDB($username);

    if ($result['status'] == 'SUCCESS'){
      $decryptedPasswrd = decryptPassword($result['passwrd']);
      $passwrd = $_POST["passwrd"];

      if ($decryptedPasswrd === $passwrd)
        { 
          startSession($username, $passwrd);
      
      echo json_encode(array("message" => "LoginMain Successful"));
      }
      
      else{
        header("HTTP/1.1 500 " . $result["status"]);
            die($result["status"]);
    };
  }else{
        header("HTTP/1.1 500 " . $result["status"]);
            die($result["status"]);}
}

function loginProf(){
  $username = $_POST["username"];

    $result = loginProfDB($username);

    if ($result['status'] == 'SUCCESS'){
      $decryptedPasswrd = decryptPassword($result['passwrd']);
      $passwrd = $_POST["passwrd"];

      if ($decryptedPasswrd === $passwrd)
        { 
          startSession($username, $passwrd);
      //Haven't created a register prof part yet, so prof login won't work.
      echo json_encode(array("message" => "LoginMain Successful"));
      }
      
      else{
        header("HTTP/1.1 500 " . $result["status"]);
            die($result["status"]);
    };
  }else{
        header("HTTP/1.1 500 " . $result["status"]);
            die($result["status"]);}
}

  function decryptPassword($passwrd){
    $key = pack('H*', "bcb04b7e103a05afe34763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
      
      $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
      
      $ciphertext_dec = base64_decode($passwrd);
      $iv_dec = substr($ciphertext_dec, 0, $iv_size);
      $ciphertext_dec = substr($ciphertext_dec, $iv_size);

      $passwrd = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);
      
      
      $count = 0;
      $length = strlen($passwrd);

      for ($i = $length - 1; $i >= 0; $i --)
      {
        if (ord($passwrd{$i}) === 0)
        {
          $count ++;
        }
      }

      $decryptedPasswrd = substr($passwrd, 0,  $length - $count); 

      return $decryptedPasswrd;
  }

  function startSession($username,$passwrd){
      session_start();
      $_SESSION["username"] = $username;
      $_SESSION["passwrd"] = $passwrd;
  }

  function logout(){
    session_start();
    unset($_SESSION["username"]);
    unset ($_SESSION["passwrd"]);
    session_destroy();

    echo json_encode(array("message" => "success"));
  }

  function retrieveCookie(){
  	$cookieName = $_POST["cookieName"];
    session_start();

  	if (isset($_COOKIE[$cookieName])) {
    echo json_encode($_COOKIE[$cookieName]);
  } else {
    die("Cookie for $cookieName is not set");
  }
  }

  function createCookie(){
  $cookieName = $_POST["cookieName"];
	$cookieValue = $_POST["cookieValue"];

	setcookie($cookieName, $cookieValue, time() + (86400 * 20), "/", "", 0); 

  if (isset($_COOKIE[$cookieName])) {
    echo json_encode($cookieValue);
  } else {
    header("Can't create cookie");
  }
  }

  function retrieveSession(){
  	session_start();

  if (isset($_SESSION["username"])) {

    echo json_encode(array("username" => $_SESSION["username"]));
  } else {
    echo json_encode("Session not set");
    die("Session is not set");
  }
  }

  function registerUser(){
            $user = $_POST['user'];
            $passwrd = $_POST['passwrd'];
            $encryptedPasswrd = encryptPassword($passwrd);

            $result = registerDB($user, $encryptedPasswrd);

            if($result["status"] == "SUCCESS"){

            echo json_encode(array("message" => "Registration Successful"));
            } else{
            header("HTTP/1.1 500 " . $result["status"]);
            die($result["status"]);
            }
  }

 function encryptPassword($passwrd)
  {

      $key = pack('H*', "bcb04b7e103a05afe34763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
      $key_size =  strlen($key);
      
      $plaintext = $passwrd;

      $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
      $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
      
      $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $plaintext, MCRYPT_MODE_CBC, $iv);
      $ciphertext = $iv . $ciphertext;
      
      $encryptedPasswrd = base64_encode($ciphertext);

      return $encryptedPasswrd;
  }

  function loadAcad()
  {
    $result = retrieveAcadThreadDB();

    if ($result["status"] == "SUCCESS") {
      echo json_encode($result["threads"]);
    } else {
      header("HTTP/1.1 500 " . $result["status"]);
      die($result["status"]);
    }
  }

  function loadAdmin()
  {
    $result = retrieveAdminThreadDB();

    if ($result["status"] == "SUCCESS") {
      echo json_encode($result["threads"]);
    } else {
      header("HTTP/1.1 500 " . $result["status"]);
      die($result["status"]);
    }
  }

  function add_topic()
  {
            $topic = $_POST['topic'];
            $content = $_POST['content'];
            $category = $_POST['category'];

            $result = addTopicDB($topic, $content, $category);

            if($result["status"] == "SUCCESS"){

            echo json_encode(array("message" => "Topic added"));
            } else{
            header("HTTP/1.1 500 " . $result["status"]);
            die($result["status"]);
            }
  }

  function add_admintopic()
  {
            $topic = $_POST['topic'];
            $content = $_POST['content'];
            $category = $_POST['category'];

            $result = addAdminTopicDB($topic, $content, $category);

            if($result["status"] == "SUCCESS"){

            echo json_encode(array("message" => "Topic added"));
            } else{
            header("HTTP/1.1 500 " . $result["status"]);
            die($result["status"]);
            }
  }

  function lastAcad()
  {
    $result = retrieveLastAcadDB();

    if ($result["status"] == "SUCCESS") {
      echo json_encode($result["lastPost"]);
    } else {
      header("HTTP/1.1 500 " . $result["status"]);
      die($result["status"]);
    }
  }

  function lastAdmin()
  {
    $result = retrieveLastAdminDB();

    if ($result["status"] == "SUCCESS") {
      echo json_encode($result["lastPost"]);
    } else {
      header("HTTP/1.1 500 " . $result["status"]);
      die($result["status"]);
    }
  }

  function load_adminthread()
  { 
    $thread = $_POST['thread'];
    $result = loadAdminThreadDB($thread);

    if ($result["status"] == "SUCCESS") {
      echo json_encode($result["lastPost"]);
    } else {
      header("HTTP/1.1 500 " . $result["status"]);
      die($result["status"]);
    }
  }
  

 ?>