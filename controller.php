<?php
    session_start();
    if(!isset($_SESSION['login'])) {
        header('Location: login.php'); die();
    }
?>
<?php
  // Written by Nicola Rainiero | rainnic.altervista.org
  // Licensed under GNU GPLv3

  // Function for basic field validation (present and neither empty nor only white space
  function IsNullOrEmptyString($str){
      return (!isset($str) || trim($str) === '');
  }

  function refreshHour() { 
  $dom = new DOMDocument();
  $dom->loadHTML(file_get_contents('./index.html'));
  $dom->validateOnParse = true; //<!-- this first

  $dom->preserveWhiteSpace = false;

  $stamp = $dom->getElementById("centered")->textContent;
  if (IsNullOrEmptyString($stamp)) {$stamp = '08:00';}
  
  $hours = substr($stamp, 0, 4); // from "08:00" returns "08:0"
  $finalMin = substr($stamp, -1); // from "08:04" returns the "4"
  $finalMin = (int)$finalMin; // convert to an integer
  if (!($finalMin % 5 == 0)) {$stamp = $hours . '0';}
  
  echo $stamp;
  }



  if (isset($_GET['refresh'])) { return refreshHour(); }



  function removeMin() { 
  $timeIn = $_GET['remove'];
  $minutes_to_remove = 5;
  $time = new DateTime($timeIn);
  $time->sub(new DateInterval('PT' . $minutes_to_remove . 'M'));

  $stamp = $time->format('H:i');
  writeFile($stamp); // call the function
  echo $stamp;}

  if (isset($_GET['remove'])) { return removeMin(); }
  
  function addMin() {
  $timeIn = $_GET['add'];
  $minutes_to_add = 5;
  $time = new DateTime($timeIn);
  $time->add(new DateInterval('PT' . $minutes_to_add . 'M'));

  $stamp = $time->format('H:i');
  writeFile($stamp); // call the function
  echo $stamp;}

  if (isset($_GET['add'])) { return addMin(); }
  
function writeFile($input)
{
$fp = fopen('index.html', 'w');
fwrite($fp, '
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>  
<meta http-equiv="content-type" content="text/html;charset=ISO-8859-1">
<meta http-equiv="refresh" content="5" />
<title>Waiting list</title>

<style type="text/css" media="screen">

html, body, #centered {
    height: 100%;
}


body 
    {
    color: white;
    background-color: #000;
    font-family: sans-serif;
    margin: 0px
    }

#centered {
    /* position: fixed; */
    display: -webkit-flexbox;
    display: -ms-flexbox;
    display: -webkit-flex;
    display: flex;
    -webkit-flex-align: center;
    -ms-flex-align: center;
    -webkit-align-items: center;
    align-items: center;
    justify-content: center;
    font-size: 30vw;
}

.hour {
    position: fixed;
    width: 100%;
    top: 0;
text-align: center;
 font-size: 8vw;
}

#bottom
    {
    position: fixed;
    bottom: 0;
    width: 100%;
    font-size: 5.0vw;
    text-align: center;
    }

</style>
</head>
<body>
<div class="hour">BOOKING TIME:<div>
<div id="centered">' . $input . '</div>
<div id="bottom">PLEASE COME TO THE ENTRANCE</div>
</body>
</html>
');
fclose($fp);
}

?>


  <!DOCTYPE html>
  <html>
  <title>Remote controller for a waiting list scheduling</title>
  <head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style type="text/css" media="screen">
html, body, #centered {
    height: 100%;
}

body 
    {
    color: black;
    background-color: #FFF;
    font-family: sans-serif;    
    margin: 0px
    }

#title
    {
    font-size: 5.0vw;
    text-align: center;
    position: fixed;
    /* bottom: 150px; */
    top: 0px;
    width: 100%;
    /* height: 20px; */
    visibility: visible;
    display: block
    }

#description {
  position: fixed; /* or absolute */
  top: 50%;
  left: 50%;
  /* bring your own prefixes */
  transform: translate(-50%, -50%);
  font-size: 2vw;
}

#centered {
    display: -webkit-flexbox;
    display: -ms-flexbox;
    display: -webkit-flex;
    display: flex;
    -webkit-flex-align: center;
    -ms-flex-align: center;
    -webkit-align-items: center;
    align-items: center;
    justify-content: center;
}

a {
text-align: center;
 font-size: 20vw;
}

#hour
    {
    position: fixed;
    bottom: 0;
    width: 100%;
    font-size: 6.0vw;
    text-align: center;
    }

/* Style buttons */
.btn {
  background-color: DodgerBlue; /* Blue background */
  border: none; /* Remove borders */
  color: white; /* White text */
  padding: 12px 16px; /* Some padding */
  font-size: 20vw; /* Set a font size */
  cursor: pointer; /* Mouse pointer on hover */
}

/* Darker background on mouse-over */
.btn:hover {
  background-color: RoyalBlue;
}

</style>
</head>
  <body onload="refreshHour(event);">
  <div id="title">WAITING LIST SCHEDULER</div>
  
  <p id="hour">08:00</p>
  
  <div id="centered">
       <button onclick="removeMin(event)" class="btn"><i class="fa fa-minus-circle"></i></button>
       <button onclick="refreshHour(event)" class="btn"><i class="fa fa-refresh"></i></button>
       <button onclick="addMin(event)" class="btn"><i class="fa fa-plus-circle"></i></button>
  </div>
  

  <script>
  async function removeMin(e) {
    e.preventDefault(); 
    time = document.getElementById("hour").innerHTML; 
    document.getElementById("hour").innerHTML = await(await fetch('?remove='+time)).text();
  }

  async function refreshHour(e) {
    e.preventDefault();
    document.getElementById("hour").innerHTML = await(await fetch('?refresh=1')).text();
  }

  async function addMin(e) {
    e.preventDefault();
    time = document.getElementById("hour").innerHTML;
    document.getElementById("hour").innerHTML = await(await fetch('?add='+time)).text();
  }
  </script>

</body>
</html>
