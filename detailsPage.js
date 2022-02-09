let TOKEN ='f8fa8140c81a4b28951b3725aa628125';
let names=[];
let addreses = [];
let details = {};
let detailsArray = [];
let a = document.getElementById("importss");
window.addEventListener("load",async function()
{

    let a = document.getElementById("importss");
    a.innerHTML ="";
    let naams = await getData();
    console.log(naams);
  

   let add = await getAddresses(names);
   console.log(add);
   let detailss = await getDetails(add);
    console.log(detailss);    

    a.innerHTML = "<table id='bigTable'><tr  id='rows'><th  id='items'  font-size:10px; >Wallet Name</th><th   id='items'  font-size:10px; >Wallet Address</th><th   id='items'  font-size:10px; >Wallet Details</th></tr>";
    for(let i=0; i<addreses.length ; i++)
    {   
        a.innerHTML+=
        "<tr  id='rows' style='width: auto; height:10px;  padding: 5px;  '>"
        +"<td  id='items' style='width: auto; height:10px;padding: 5px; '>"+ names[i] +"</td>" +
        "<td   id='items' style='width: auto; height:10px;padding: 5px;'> ['"+ addreses[i] +"']</td>"
        +"<td  id='items' style='width: auto; height:10px; padding: 5px;'> Final Balance: "+ detailsArray[i].final+" Test Bitcoins <br>"+
            "Total Sent Amount: "+ detailsArray[i].sent + " Test Bitcoins <br>"+ 
            "Total Unconfirmed Amount: "+ detailsArray[i].unconfirmed+ " Test Bitcoins <br>"+ 
        "</td></tr>";
    }
    a.innerHTML += "</table>";
    
});

async function getData()
{
    await $.get('https://api.blockcypher.com/v1/btc/test3/wallets?token='+ TOKEN)
    .then(function(d) {
        console.log(d);
        names=d.wallet_names;
    });

    return names;
}

async function getAddresses(names)
{
    for (let i=0; i<names.length ; i++)
    {
        await $.get('https://api.blockcypher.com/v1/btc/test3/wallets/'+names[i]+'/addresses?token='+TOKEN)
        .then(function(d) {
        addreses.push(d.addresses);
    });
    }
    return addreses;
}

async function getDetails(addreses)
{
    for (let i=0; i<addreses.length ; i++)
    {
       await $.get('https://api.blockcypher.com/v1/btc/test3/addrs/'+ addreses[i])
        .then(function(d) {
 
            details = {
            "final": d.final_balance,
            "sent": d.total_sent,
            "unconfirmed": d.unconfirmed_balance
        }
        detailsArray.push(details);
    });
    }
    return detailsArray;
}
