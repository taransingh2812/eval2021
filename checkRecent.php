<?php

    include "connect.php";
    
    $ipaddress =  filter_input(INPUT_GET, "ipaddress" , FILTER_SANITIZE_STRING);
    

    // return 1 for invalid input
    // return 0 if transcation was already there in database and was atleast 30 min old
    // return -1 if transaction was already there in database but was less than 30 min, 
    // therefore, can't be completed.
    // return 2 if its a new ip address and store it in the database 
    //after the transcation is completed
    
    $returnSt = -1;

    if(trim($ipaddress)==="" || trim($ipaddress)===null)
    {
        $returnSt = 1;
    }


    //check for the address in recent transaction

    $cmd = "SELECT * FROM `Recents` WHERE `ipaddress`= ? ORDER BY `ipaddress`ASC, `Time` DESC;";
    $stmt = $dbh->prepare($cmd);
    $param = [$ipaddress];
    $success = $stmt->execute($param);

   // $timeT = false;
    $time = new DateTime();
    if($row = $stmt->fetch())
    {
        $time = $row["Time"];
        if(strtotime($time) > strtotime("-30 minutes")) {
            $returnSt = 0;
        }
        else 
        //it means tell user that this can't be done as the last transaction is just less than 30 min old
        // tell them to wait for atleast 30 min
        { $returnSt= -1;}
    }
    else{
        
        $returnSt = 2;
    }
 
    echo json_encode($returnSt);

?>
