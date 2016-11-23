<?php

	function connectionToDataBase(){
		$servername = "localhost";
		$username = "root";
		$password = "";
		$dbname = "myProject";

		$conn = new mysqli($servername, $username, $password, $dbname);
		
		if ($conn->connect_error){
			return null;
		}
		else{
			return $conn;
		}
	}

	function loginStudentDB($username){

		$conn = connectionToDataBase();

		if ($conn != null){
			$sql = "SELECT student_id, student_pass FROM student WHERE student_id='$username'";
		
			$result = $conn->query($sql);

			if ($result->num_rows > 0)
			{
				while ($row = $result->fetch_assoc()) {

				$conn -> close();

				return array("status" => "SUCCESS", "username" => $row["student_id"], "passwrd" => $row["student_pass"]);
			}
			}
			else{
				$conn -> close();
				return array("status" => "USERNAME NOT FOUND");
			}
		}else{
			$conn -> close();
			return array("status" => "CONNECTION WITH DB WENT WRONG");
		}
	}

	function loginProfDB($username){

		$conn = connectionToDataBase();

		if ($conn != null){
			$sql = "SELECT admin_id, admin_pass FROM admin WHERE admin_id='$username'";
		
			$result = $conn->query($sql);

			if ($result->num_rows > 0)
			{
				while ($row = $result->fetch_assoc()) {

				$conn -> close();

				return array("status" => "SUCCESS", "username" => $row["admin_id"], "passwrd" => $row["admin_pass"]);
			}
			}
			else{
				$conn -> close();
				return array("status" => "USERNAME NOT FOUND");
			}
		}else{
			$conn -> close();
			return array("status" => "CONNECTION WITH DB WENT WRONG");
		}
	}

		function registerDB($user, $encryptedPasswrd){
			$conn = connectionToDataBase();

			if ($conn != null){
			$sql = "SELECT * FROM availableStudent WHERE student_id = '$user'";		
			$result = $conn->query($sql);

			if ($result->num_rows == 0)
			{
				$conn -> close();
				return array("status" => "ALREADY IN USE");
			}
			else{
				$sql = "INSERT INTO student(student_id, student_pass) VALUES ('$user', '$encryptedPasswrd')";
				$sql2 = "DELETE FROM availableStudent WHERE student_id = '$user'";

				 if ((mysqli_query($conn, $sql)) && (mysqli_query($conn, $sql2)))
            {
                $conn -> close();
				return array("status" => "SUCCESS");
            }else{
				$conn -> close();
				return array("status" => "FAILURE IN INSERTION");
            }
				
			}
		}else{
			$conn -> close();
			return array("status" => "CONNECTION WITH DB WENT WRONG");
		}

		}

		function retrieveAcadThreadDB(){

			$conn = connectionToDataBase();

			if ($conn != null){
			$sql = "SELECT topic_subject, topic_by, date(topic_date) FROM topics ORDER BY topic_date DESC LIMIT 10";

			$result = $conn->query($sql);

			$threads = array();
		if ($result->num_rows > 0) { //Apparently selecting a non-object here.
			while ($row = $result->fetch_assoc()) {

				array_push($threads, array("threads" => $row["topic_subject"], "users" => $row["topic_by"], "date" => $row["date(topic_date"]));
        }
        $conn->close();
		return array("status" => "SUCCESS", "threads" => $threads);
	}

         else {
        $conn->close();
        return array("status" => "COULDN'T LOAD THREADS");
      }
    } else {
      $conn->close();
      return array("status" => "CONNECTION WITH DB WENT WRONG");
    }
		
		}

		function retrieveAdminThreadDB(){

			$conn = connectionToDataBase();

			if ($conn != null){
			$sql = "SELECT topic_subject, topic_by, date(topic_date) FROM admintopics ORDER BY topic_date DESC LIMIT 10";

			$result = $conn->query($sql);

			$threads = array();
		if ($result->num_rows > 0) { //Apparently selecting a non-object here.
			while ($row = $result->fetch_assoc()) {

				array_push($threads, array("threads" => $row["topic_subject"], "users" => $row["topic_by"], "date" => $row["date(topic_date)"]));
        }
        $conn->close();
		return array("status" => "SUCCESS", "threads" => $threads);
	}

         else {
        $conn->close();
        return array("status" => "COULDN'T LOAD THREADS");
      }
    } else {
      $conn->close();
      return array("status" => "CONNECTION WITH DB WENT WRONG");
    }
		
		}

		function addTopicDB($topic, $content, $category){
			$conn = connectionToDataBase();

			if ($conn != null){
			session_start();
    		$username = $_SESSION["username"];
    		$sql = "INSERT INTO topics (topic_subject, topic_by, topic_date, topic_cat) VALUES ('$topic','$username', NOW(), '$category')";
    		$sql2 = "INSERT INTO posts (topic_subject, post_content, post_by, post_date) VALUES ('$topic', '$content', '$username', NOW())";		

				 if ((mysqli_query($conn, $sql)) && (mysqli_query($conn, $sql2)))
            {
                $conn -> close();
				return array("status" => "SUCCESS");
            }else{
				$conn -> close();
				return array("status" => "FAILURE IN INSERTION");
            }
				
			}
		else{
			$conn -> close();
			return array("status" => "CONNECTION WITH DB WENT WRONG");
		}
		}

		function addAdminTopicDB($topic, $content, $category){
			$conn = connectionToDataBase();

			if ($conn != null){
			session_start();
    		$username = $_SESSION["username"];
    		$sql = "INSERT INTO admintopics (topic_subject, topic_by, topic_date, topic_cat) VALUES ('$topic','$username', NOW(), '$category')";
    		$sql2 = "INSERT INTO adminposts (topic_subject, post_content, post_by, post_date) VALUES ('$topic', '$content', '$username', NOW())";		

				 if ((mysqli_query($conn, $sql)) && (mysqli_query($conn, $sql2)))
            {
                $conn -> close();
				return array("status" => "SUCCESS");
            }else{
				$conn -> close();
				return array("status" => "FAILURE IN INSERTION");
            }
				
			}
		else{
			$conn -> close();
			return array("status" => "CONNECTION WITH DB WENT WRONG");
		}
		}

		function retrieveLastAcadDB(){
			$conn = connectionToDataBase();

			if ($conn != null){

    		$sql = "SELECT topic_subject, topic_by, topic_date from topics ORDER BY topic_date DESC LIMIT 1";
			$result = $conn->query($sql);

			$lastPost = array();
		if ($result->num_rows > 0){
			while ($row = $result->fetch_assoc()) {  //Apparently selecting a non-object here.
	
				array_push($lastPost, array("lastPost" => $row["topic_subject"], "user" => $row["topic_by"]));
        }
        $conn->close();
		return array("status" => "SUCCESS", "lastPost" => $lastPost);
	}
            else{
				$conn -> close();
				return array("status" => "FAILURE IN RETRIEVAL");
            }
				
			}
		else{
			$conn -> close();
			return array("status" => "CONNECTION WITH DB WENT WRONG");
		}
	}

		function retrieveLastAdminDB(){
			$conn = connectionToDataBase();

			if ($conn != null){

    		$sql = "SELECT topic_subject, topic_by, topic_date from admintopics ORDER BY topic_date DESC LIMIT 1";
			$result = $conn->query($sql);

			$lastPost = array();
		if ($result->num_rows > 0){
			while ($row = $result->fetch_assoc()) {  //Apparently selecting a non-object here.
	
				array_push($lastPost, array("lastPost" => $row["topic_subject"], "user" => $row["topic_by"]));
        }
        $conn->close();
		return array("status" => "SUCCESS", "lastPost" => $lastPost);
	}
            else{
				$conn -> close();
				return array("status" => "FAILURE IN RETRIEVAL");
            }
				
			}
		else{
			$conn -> close();
			return array("status" => "CONNECTION WITH DB WENT WRONG");
		}
	}

	function loadAdminThreadDB($thread){
		$conn = connectionToDataBase();

			if ($conn != null){
			$sql = "SELECT topic_subject, post_content, post_date, post_by FROM adminposts ORDER BY post_date DESC LIMIT 10 Where topic_subject='$thread'";

			$result = $conn->query($sql);

			$threads = array();
		if ($result->num_rows > 0) { 
			while ($row = $result->fetch_assoc()) {

				array_push($threads, array("content" => $row["post_content"], "users" => $row["post_by"], "date" => $row["post_date"]));
        }
        $conn->close();
		return array("status" => "SUCCESS", "threads" => $threads);
	}

         else {
        $conn->close();
        return array("status" => "COULDN'T LOAD THREADS");
      }
    } else {
      $conn->close();
      return array("status" => "CONNECTION WITH DB WENT WRONG");
    }
	}

?>