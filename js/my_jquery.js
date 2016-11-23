$(document).ready(function(){
    retrieveCookie();
    checkSession();
    $("#logoutTab").hide();
    $("#homeTab").hide();

    /*Student login, with cookie created if Remember Me*/

	$("#loginStudentButton").on("click", function(event){
        event.preventDefault();

		var jsonData = {
                            "action": "LOGINSTUDENT",
                            "username" : $("#studentUser").val(),
                            "passwrd" : $("#studentPassword").val()
                            };

                    $.ajax({
                    url : "data/applicationLayer.php",
                    type : "POST",
                    data : jsonData,
                    dataType : "json",
                    contentType : "application/x-www-form-urlencoded",
                    success : function(jsonData){
                        window.location.replace("home.html");
                        var userHeader = 'Hello <strong>' + $("#studentUser").val() + 'Welcome!';
                        $("#userbar").html(userHeader).show(1000);
                        alert("Success");
                        if ($('#rememberMe').is(':checked')){
                            createCookie("username", $("#studentUser").val());
                        }
                        
                    },
                    error: function(errorMsg){
                            alert(errorMsg.statusText);
                    }
                });
            });

/*Prof login, with cookie created if Remember Me*/
    
    $("#loginProfButton").on("click", function(event){
        event.preventDefault();

        var jsonData = {
                            "action": "LOGINPROF",
                            "username" : $("#profUser").val(),
                            "passwrd" : $("#profPassword").val()
                            };

                    $.ajax({
                    url : "data/applicationLayer.php",
                    type : "POST",
                    data : jsonData,
                    dataType : "json",
                    contentType : "application/x-www-form-urlencoded",
                    success : function(jsonData){
                        window.location.replace("home.html");
                        var userHeader = 'Hello <strong>' + $("#profUser").val() + '</strong>. Welcome!';
                        $("#userbar").html(userHeader).show(1000);
                        alert("Success");
                        if ($('#rememberMe').is(':checked')){
                            createCookie("profUser", $("#profUser").val());
                        }
                        
                    },
                    error: function(errorMsg){
                            alert(errorMsg.statusText);
                    }
                });
            });

	function createCookie (cookieName,cookieValue){
            var jsonData ={
                "action" : "CREATE_COOKIE",
                "cookieName": cookieName,
                "cookieValue": cookieValue
            };

            $.ajax({
            url: "data/applicationLayer.php",
            type: "POST",
            data: jsonData,
            dataType: "json",
            contentType: "application/x-www-form-urlencoded",
            success: function(response) {
                console.log("Cookie Created");

            },
            error: function(error) {
                console.log(error);
            }
            });
        };

        /*Student Register*/

    $("#registerButton").on("click", function(event) {
                        
                        event.preventDefault();
                        var user = $(this).parent().find("[name='uname']")[0].value;
                        var passwrd = $(this).parent().find("[name='psw']")[0].value;
                        var passwrd2 = $(this).parent().find("[name='psw2']")[0].value;                          

                        if (user.length != 8){
                                $("#errorMessage").html("Please enter a valid Matriculation Number before sending!");
                        }
                        else if (passwrd != passwrd2){
                               $("#errorMessage").html("Please ensure your passwords match before sending!");
                        }
                        else                            
                            registerUser(user, passwrd);
                    });

    function registerUser(user, passwrd){
                                var jsonArray = {
                                "user" : user,
                                "passwrd" : passwrd,
                                "action": "REGISTER_USER"
                                };                              
                                
                        $.ajax({
                        type: "POST",
                        url: "data/applicationLayer.php",
                        data : jsonArray,
                        dataType : "json",
                        contentType : "application/x-www-form-urlencoded",
                        success: function(jsonArray) {
                            alert("Registered! Try logging in."); 
                            window.location.replace("index.html");                          
                        },
                        error: function(errorMsg){
                            alert(errorMsg.statusText);
                        }

                        });
                    };
                    
    function retrieveCookie(){
                        var jsonData = {
                        "action" : "RETRIEVE_COOKIE",
                        "cookieName": "username"
                        };

                    $.ajax({
                     url: "data/applicationLayer.php",
                        type: "POST",
                        data: jsonData,
                        dataType: "json",
                        contentType: "application/x-www-form-urlencoded",
                        success: function(response) {
                             $("#studentUser")[0].value = response;
                            console.log("Cookie retrieved");
                            },
                            error: function(error) {
                            console.log(error);
                            alert("No cookie retrieved");
                            }
                            });

                        }
    /*If session started, change user display accordingly*/
    function checkSession(){
            
            var jsonData = {
            "action": "RETRIEVE_SESSION",
            }
            $.ajax({
                url: 'data/applicationLayer.php', 
                type: 'POST',
                data: jsonData,
                dataType: 'json',
                contentType : "application/x-www-form-urlencoded",
                success: function(jsonData){

                var userHeader = 'Hello <strong>' + jsonData.username + '</strong>. Welcome!';
                $("#userbar").html(userHeader).show(1000);
                $("#aboutUsTab").hide();
                $("#registerTab").hide();
                $("#loginTab").hide();
                $("#homeTab").show();
                $("#logoutTab").show();

                },
                error: function(error) {
                console.log("Session not active")
                 }
                });
             }

    //Logout functionality for both index.html and home.html
         
    $("#logoutTab").on("click", function(){
                logout();
         });
    $("#logoutTab2").on("click", function(){
                logout();
         });

                                            
                function logout(){
                          var jsonData = {
                            "action": "LOGOUT",
                            };

                    $.ajax({
                    url : "data/applicationLayer.php",
                    type : "POST",
                    data : jsonData,
                    dataType : "json",
                    contentType : "application/x-www-form-urlencoded",
                    success : function(jsonData){
                        window.location.replace("index.html"); 
                        var userHeader = 'You have logged out!';

                        $("#userbar").html(userHeader).show(1000);
                        $("#aboutUsTab").show();
                        $("#registerTab").show();
                        $("#loginTab").show();
                        $("#homeTab").hide();
                        $("#logoutTab").hide();
                    },
                    error : function(errorMessage) {
                        alert("Problem with logout");
                    }
                });                                                             
                }

                //on loading of forum thread page, load the most recent threads
    $("home.html").ready(function(){
        $("#acadTable").hide();
        $("adminTable").hide();
        loadLastTopicAcad();
        loadLastTopicAdmin();

        function loadLastTopicAcad(){
            var jsonData = {
        "action" : "LOAD_LAST_ACAD",
       }
    $.ajax({
        url: "data/applicationLayer.php", 
        data : jsonData,
        dataType: "json",
        type: "POST",
        success: function (data) {
            console.log("Topics loaded");

            var newHTMLContent = "" + data[0].lastPost + " by " + data[0].user; 

            $("#lastPostAcad").append(newHTMLContent);

            
        },
        error: function (errorMsg) {
            console.log(errorMsg);
        }
    });
        }

        function loadLastTopicAdmin(){
            var jsonData = {
        "action" : "LOAD_LAST_ADMIN",
       }
    $.ajax({
        url: "data/applicationLayer.php", 
        data : jsonData,
        dataType: "json",
        type: "POST",
        success: function (data) {
            console.log("Topics loaded");

            var newHTMLContent = "" + data[0].lastPost + " by " + data[0].user; 

            $("#lastPostAdmin").append(newHTMLContent);

            
        },
        error: function (errorMsg) {
            console.log(errorMsg);
        }
    });
        }

//function to add topic

    $("#addTopic").on("click", function(event) {
                        
                        event.preventDefault();
                        var topic = $(this).parent().find("[name='topicName']")[0].value;
                        var content = $(this).parent().find("[name='topicContent']")[0].value;
                        var category = $(this).parent().find("[name='category']:checked")[0].value;
                        if (category == "acad")
                            {
                            addTopic(topic, content, category);
                        }
                        else addAdminTopic(topic, content, category);
                    });

    function addTopic(topic, content, category){
                                var jsonArray = {
                                "topic" : topic,
                                "content" : content,
                                "category" : category,
                                "action": "ADDTOPIC"
                                };                              
                                
                        $.ajax({
                        type: "POST",
                        url: "data/applicationLayer.php",
                        data : jsonArray,
                        dataType : "json",
                        contentType : "application/x-www-form-urlencoded",
                        success: function(jsonArray) {
                            alert("Topic posted!"); 
                            window.location.replace("home.html");                          
                        },
                        error: function(errorMsg){
                            alert(errorMsg.statusText);
                        }

                        });
                    };

    function addAdminTopic(topic, content, category){
                                var jsonArray = {
                                "topic" : topic,
                                "content" : content,
                                "category" : category,
                                "action": "ADDADMINTOPIC"
                                };                              
                                
                        $.ajax({
                        type: "POST",
                        url: "data/applicationLayer.php",
                        data : jsonArray,
                        dataType : "json",
                        contentType : "application/x-www-form-urlencoded",
                        success: function(jsonArray) {
                            alert("Topic posted!"); 
                            window.location.replace("home.html");                          
                        },
                        error: function(errorMsg){
                            alert(errorMsg.statusText);
                        }

                        });
                    };

        //Show threads within each category upon clicking the tab
                                                                                                      
    $("#acadTab").on("click", function(){
        $("#acadTable").show(1000);
        $("#adminTable").hide();
    var jsonData = {
        "action" : "LOAD_ACAD"
       }
    $.ajax({
        url: "data/applicationLayer.php", 
        data : jsonData,
        dataType: "json",
        type: "POST",
        success: function (data) {
            console.log("Threads loaded correctly");

            $("#acadTable").html("<tr><th class='leftpart'>Topics</th><th class='rightpart'>Created By</th></tr>")


            var newHTMLContent = ""; 

             for (i = 0; i < data.length; i++) {
                        newHTMLContent += '<tr><td class="leftpart">';
                        newHTMLContent += '<h3><a href="admin.html">' + data[i].threads + '</a></h3></td>';
                        newHTMLContent += '<td class="rightpart">';
                        newHTMLContent += '<h3><a href="admin.html">' + data[i].users + '</a></h3>' + "on " + data[i].date + '</td></tr>';
                      };

            $("#acadTable").append(newHTMLContent);

            
        },
        error: function (errorMsg) {
            console.log(0);
        }
    });


    })

$("#adminTab").on("click", function(){
        $("#adminTable").show(1000);
        $("#acadTable").hide();
    var jsonData = {
        "action" : "LOAD_ADMIN"
       }
    $.ajax({
        url: "data/applicationLayer.php", 
        data : jsonData,
        dataType: "json",
        type: "POST",
        success: function (data) {
            console.log("Threads loaded correctly");

             $("#adminTable").html("<tr><th class='leftpart'>Topics</th><th class='rightpart'>Created By</th></tr>")

            var newHTMLContent = ""; 

             for (i = 0; i < data.length; i++) {
                        newHTMLContent += '<tr><td class="leftpart">'; 
                        newHTMLContent += '<h3><a onclick="getAdminThread(data[i].threads)">' + data[i].threads + '</a></h3></td>'; //functionality is not working...
                        newHTMLContent += '<td class="rightpart">';
                        newHTMLContent += '<h3>' + data[i].users + '</h3>' + "on " + data[i].date + '</td></tr>';
                      };

            $("#adminTable").append(newHTMLContent);
        

        },
        error: function (errorMsg) {
            console.log(errorMsg);
        }
    });


    })

//Not working at all. Supposed to retrieved the corresponding comments within the thread selected.
function getAdminThread(thread){
    var jsonData = {
        "thread" : thread,
        "action" : "LOAD_ADMINTHREAD"
       }

       $.ajax({
        url: "data/applicationLayer.php", 
        data : jsonData,
        dataType: "json",
        type: "POST",
        success: function (data) {
            console.log(" loaded correctly");        

        },
        error: function (errorMsg) {
            console.log(errorMsg);
        }
    });

}
        });
})