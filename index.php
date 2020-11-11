<?php 

  /* Connect to DB */
  $url = parse_url(getenv("CLEARDB_DATABASE_URL"));
          $server = $url["host"];
          $username = $url["user"];
          $password = $url["pass"];
          $db = substr($url["path"], 1);

  $conn = new mysqli($server, $username, $password, $db);

  if ($conn->connect_errno){
    echo "Failed to connect to MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error;
  }

  /* DATA GET */
  /* API call here to grab most popular repos */
    function getData($url){
      $ch = curl_init();
      curl_setopt($ch,CURLOPT_URL,$url);
      curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
      curl_setopt($ch,CURLOPT_HEADER, true );
      curl_setopt($ch,CURLOPT_USERAGENT, "mdutro002");
      $output=curl_exec($ch);
      //parse out json
      $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
      $header = substr($output, false, $header_size);
      $body = substr($output, $header_size);
      curl_close($ch);
      return json_decode($body, true);
    }

    $rawData = getData("https://api.github.com/search/repositories?q=language:php&sort=stars&per_page=3");
    //This URL filters by language, sorts by stars, and limits 3 results - Todo: modify to accept wildcard params?

    $repos = $rawData[items];
    $cleanData = [];

    foreach($repos as $repo){
      $repoRow = [];
      $rid = $repo[id];
      $rname = $repo[name];
      $rdate = $repo[created_at];
      $rpush = $repo[pushed_at];
      $rdesc = $repo[description];
      $rstars = $repo[stargazers_count];

      $paramValues = '%s, %s, %s, %s, %s, %d'
      sprintf($paramValues, $rid, $rname, $rdate, $rpush, $rdesc, $rstars);
      //SQL would look akin to the following:
      //INSERT INTO php_repos (gitID, name, created, pushed, description, stars)
       //VALUES ($1, $2, $3, $4, $5, $6);
    }

  /* DATA DISPLAY */
   function pullData(){
    $freshPull = $conn->query("SELECT * FROM php_repos");
    echo $freshPull;
   }

   pullData();

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

<!-- DATA WILL GO DOWN HERE - conditional formatting, etc -->
  <?php 

  ?>
</body>
</html>