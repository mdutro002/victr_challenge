<?php 

  /* Connect to DB */
 /*  
  $url = parse_url(getenv("CLEARDB_DATABASE_URL"));
          $server = $url["host"];
          $username = $url["user"];
          $password = $url["pass"];
          $db = substr($url["path"], 1);

          $conn = new mysqli($server, $username, $password, $db);
 */

  /* DATA GET */
  /* API call here to grab most popular repos */
    function getData($url){
      $ch = curl_init();
      curl_setopt($ch,CURLOPT_URL,$url);
      curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
      curl_setopt($ch,CURLOPT_HEADER, true );
      curl_setopt($ch,CURLOPT_USERAGENT, "mdutro002");
      $output=curl_exec($ch);
      curl_close($ch);
      return $output;
    }

    echo getData("https://api.github.com/search/repositories?q=language:php&sort=stars&per_page=3");
    //This URL filters by language, sorts by stars, and limits 3 results - variables can be shifted
    /*   
    this didn't quite work - might save and tweak for later use
      $json_data = json_decode(file_get_contents('https://api.github.com/search/repositories?q=language:php&sort=stars&per_page=3'));
        var_dump($json_data);
    */

    /* Sanatize Data & write to DB */
      /* Parse relevant JSON Fields  */
      /* Loop and write each result to DB */

  /* DATA DISPLAY */

  /* Database data pull here */

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>PHP Repos</title>
  <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
</head>
<body>
  <h1>Github Allstars - PHP edition</h1>
  <button>Get Data</button>

<!-- DATA WILL GO DOWN HERE - conditional formatting, etc -->
  <?php 
  ?>
  
</body>
</html>