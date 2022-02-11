<?php
include "connect.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="require.js"></script>
    <link href="style.css" rel="stylesheet"/>

   <script>

        //My Wallet Name: Adam address: mpUc1c2sn9Ri93REoCD6vGspD6HkpGtdHP
        let address = "mpUc1c2sn9Ri93REoCD6vGspD6HkpGtdHP";
        let TOKEN = 'f8fa8140c81a4b28951b3725aa628125';
        var ipadd = "";
    
       window.addEventListener("load",async function(e)
    {
        e.preventDefault();
      
        let address = 'mpUc1c2sn9Ri93REoCD6vGspD6HkpGtdHP';
        let TOKEN = 'f8fa8140c81a4b28951b3725aa628125';
        var ipadd = '';

        document.getElementById("statusBar").innerHTML="";
        
        document.getElementById("balance").innerHTML = await balanceRetrieve()+ " ";

        document.getElementById("send").addEventListener("click", async function()
        {
            let toAdd = document.getElementById('toAdd').value;
            let amtAdd = 0;
            amtAdd = document.getElementById('amt').value;
            
            let val =  await checkValidAddress(toAdd);
            if(val===0){return;}
            
            await amountAddToAddr(toAdd,amtAdd);
        });
        
     
    });

    async function getRecents()
    {
        $.get('https://api.blockcypher.com/v1/btc/test3/addrs/'+ address)
        .then(function(code)
        {

        });
    }


    async function checkValidAddress(toAddd)//checkAdd
    {
        val = 0;
        await fetch('https://api.blockcypher.com/v1/btc/test3/addrs/'+ toAddd, 
                 {method: 'get'})
                 .then(async response=>{
                    if (!response.ok) {
                        // get error message from body or default to response status
                        const error = (data && data.message) || response.status;
                        return 0;
                 }
                
                 }).then(function(code){
                    val = 1;
                 })

                 .catch((error)=>{
                    val=0;
                    console.log(val);
                })
                console.log(val);
                return val; 
    }  
        
        // this adds amount to provided address as parameter
    async function amountAddToAddr(addr,amount){
        
        //check if the address is valid

        //get ip 
        await fetch('https://api.ipify.org?format=json', {method: 'get'})
        .then( async response=>{

            if (!response.ok) {
                        // get error message from body or default to response status
                        const error = (data && data.message) || response.status;
                        document.getElementById("statusBar").style.visibility="visible";
                        document.getElementById("statusBar").style.backgroundColor="red";
                        document.getElementById("statusBar").innerHTML = "No proper ip found";
                        return console.error("No proper IP found");
                 }
                 return response.json();
               
            })
            .then(await function(code)
                 {
                    ipadd = code.ip;
                    console.log(ipadd);
                 })

        //check if ip already, in database

       // my ipadd='192.123.232.232';
        let url = 'checkRecent.php?ipaddress='+ ipadd;
        await fetch(url, {method:'get'})
        .then(async response=>{

                    if (!response.ok) {
                        // get error message from body or default to response status
                        const error = (data && data.message) || response.status;
                        document.getElementById("statusBar").style.visibility="visible";
                        document.getElementById("statusBar").style.backgroundColor="red";
                        document.getElementById("statusBar").innerHTML = "Something Went WRONG";
                        return Promise.reject(error);    
                    }
                    return response.json();
            })
                 .then(async function(code){
                    console.log(code);
                    console.log(ipadd);
                    if(code===1)
                    {
                        document.getElementById("statusBar").style.visibility="visible";
                        document.getElementById("statusBar").style.backgroundColor="red";
                        document.getElementById("statusBar").innerHTML = "User Input Was Invalid";
                    }
                    else if(code===2)
                    {   // this address is the receiver's address
                        let hashValue= await transferAmt(addr,amount);
                        console.log(hashValue);
                        let ret = await addDatabase(ipadd,addr,amount/100,hashValue); 
                           
                        if(ret===1)
                        { document.getElementById("statusBar").style.backgroundColor="lightgreen";
                         document.getElementById("statusBar").innerHTML = "Transaction was Successful";
                         balanceRetrieve();
                        }           //insert it to the database
                      
                    }
                    else if(code===-1)
                    {
                        console.log(ipadd);
                        console.log("Your IP:"+ ipadd + " needs to wait for some time.");

                        document.getElementById("statusBar").style.backgroundColor="red";
                        document.getElementById("statusBar").innerHTML = "Your IP: "+ ipadd + " commited a recent transaction in less than 30 min ago.";
                        //user needs to wait as its less than 30 min rn.
                    }
                    else if(code===0)
                    { //ipadd,addr,amount,hash
                   
                        let hashValue= await transferAmt(addr,amount);
                        let ret = await addDatabase(ipadd,addr,amount/100,hashValue); 
                        console.log(hashValue);
                         
                        if(ret===1){
                        document.getElementById("statusBar").style.backgroundColor="lightgreen";
                        document.getElementById("statusBar").innerHTML = "Transaction was Successful";
                        balanceRetrieve();
                        }
                                    //update it to the database
                    }
                })
               
              
         }

        async function transferAmt(sendTo,toTransfer)
        {   
            var hashR = "";
            toTransfer = toTransfer/100;
            var newtx = {inputs: [{addresses: [address]}],
                outputs: [{addresses: [sendTo], value: toTransfer}]};

            await $.post('https://api.blockcypher.com/v1/btc/test3/txs/new', JSON.stringify(newtx))
            .then(async response=>{

                if (response.ok) {
                    // get error message from body or default to response status
                    document.getElementById("statusBar").style.visibility="visible";
                    document.getElementById("statusBar").style.backgroundColor="red";
                    document.getElementById("statusBar").innerHTML = "Something Went WRONG";
                    return "Something Went WRONG";    
                }
                return response.json();
            }).then(function(code){
                    console.log(code); 
                    hashR = code.tx.hash;
                     // add output address parameter on this function
                })
                return hashR;    
        }
         
         async function balanceRetrieve(){
             let a = 0;
          await $.get('https://api.blockcypher.com/v1/btc/test3/addrs/'+ address +'/balance' )
                .then(function(code){
                
                    console.log(code);
                    console.log("Current Balance: BTC "+ code.final_balance+ " coins.");
                    a = code.final_balance;
                });
                return a;
        /** outcome example
         * address: "12f7XNN2sqvWbbgafM5oB9tPnhgw4d6cxd"
            balance: 0
            final_balance: 0
            final_n_tx: 0
            n_tx: 0
            total_received: 0
            total_sent: 0
            unconfirmed_balance: 0
            unconfirmed_n_tx: 0
         */
        }

    

        //adding the recent stuff to the database named Recents
        //using insert.php
    async function addDatabase(ipadd,addr,amount,hashValue)
    {
        let url = 'insert.php?ipaddress='+ ipadd + '&address=' + addr +'&amount=' + amount+ '&hashValue='+ hashValue;
        console.log(url);
        let re=0;

              fetch(url, {credentials:'include'})
              .then(response=>response.json())
                .then(function(code){
                    console.log(url);
                    re=code;
                    console.log(re);
                })
                return re;
                 
    }
    
    
        //similar to retrieve balance but gives some extra information as well
        // contains history of prev transactions
    function getDetailedSummary()
    {
        $.get('https://api.blockcypher.com/v1/btc/test3/addrs/mzUcQU4yBM6asv34idf512y9Akcpzhub5v')
            .then(function(d) {console.log(d)});
    }

        //this area can be used to create new wallet with the assigned token
        //it can be useful to make multiple wallets at last
        // But this assigns new names to token but with no address 
        // FOR PRACTICE ONLY
  //created 5 wallets using this
    async function createWallet(){
        name = "";
        let arr = [];
        let al = "";
        arr.push(al);
 
        var data = {"name": name,"addresses": arr }; 

        $.post('https://api.blockcypher.com/v1/btc/test3/wallets?token='+TOKEN,
            JSON.stringify(data))
                .then(function(d) {
                console.log(name);
                console.log(d);
               
            });    
        }

          //this generates a new bcy test address and returns that address
            // returns new address related to TOKEN
            // PRACTICE PURPOSE only
            // Just kept here to make changes on developer's end.
    async function generateAdd(){
            let add="";
           
             $.post('https://api.blockcypher.com/v1/btc/test3/addrs?token='+ TOKEN)
                .then(function(d) {
                    console.log(d);
                    add = d.address;
                    console.log(add);
                    amountAddToAddr(add,100000);
                    return add;
                });
        }
     
        
        //for practice only
        // to generate address and add to an existing wallet
        // Just kept here to make changes on developer's end.
        function genaddWallet()
        {   
            
            $.post('https://api.blockcypher.com/v1/btc/test3/wallets/Taran/addresses/generate?token='+TOKEN)
            .then(function(d) {console.log(d)});
        }


        // "to get addresses"to practice transferring btc.
        // Just kept here to make changes on developer's end.
        async function getAddr(){
       
            await $.post('https://api.blockcypher.com/v1/btc/test3/addrs')
                .then(function(code)
                {   //for practice purpose
                    
                    name="baja"
                    address = code.address;
                    private = code.private;
                    public = code.public;
                    wif= code.wif;

              
                    console.log("Name: "+ name +" & address = "+ address )
  
                })
        }
            
        //use to clear wallets
        // Just kept here to make changes on developer's end.
        function deleteWallet()
        {
            $.ajax({
            url: "https://api.blockcypher.com/v1/btc/test3/wallets/Taran?token="+ TOKEN,
            method: "DELETE"}).then(function(){
                console.log("Wallet Deleted");
            })    
        }

        /**
         * All personal WALLETS(ADDRESSES) CREATED FOR THIS PROJECT
         * mzrjJYeuqsSVHZzLyMjf2frBt8Z6BLUWan , 
         * mpUc1c2sn9Ri93REoCD6vGspD6HkpGtdHP , 
         * mzUcQU4yBM6asv34idf512y9Akcpzhub5v , 
         * mvhQ4ipandBCDKkhUnPnRUe8sKR9nkRirb , 
         * mp7JHu2GvBBnFKG34SrUhhFEEuKsszr69x
         */
       </script>

    <title>Wallet</title>
</head>
<body>
<h1>Bitcoin Chain Wallet</h1>

<h2 >The wallet</h2>

<div class="ownAcc">
<h3>Balance: &#8383 <span id="balance" > </span>  </h3>
</div>

<div id="statusBar"></div>
<br><br>
<div id="forums">
<input type="text" id="toAdd" placeholder="Receiver Address?" required/>


<input type="number" id="amt" min="0" value="100000"  placeholder="How Much?" required/>
<button id="send" style="background-color:cornflowerblue ; color:whitesmoke">Send Bitcoins</button>

<br><br>

<button id="info" " onclick="location.href='details.php'">Get INFO?</button>

<br><br>

<div id="recentTrans">
    <img src="653684EF-6527-4261-899A-7DA7827B4094.jpeg" alt="QR CODE"/>
    <span id="allData">
    </span>
    </div>
</div>
</body>
</html>
