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

  /* DATA GET & WRITE */
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

    $rawData = getData("https://api.github.com/search/repositories?q=language:php&sort=stars&per_page=10");
    //This URL filters by language, sorts by stars, and limits 3 results - Todo: modify to accept wildcard params?

    $repos = $rawData[items];

    //loop through and write pertinent repo fields to MySQL Table php_repos
    //BUG - Data getting written twice each time - HOW?
    foreach($repos as $repo){
      $rid = $repo[id];
      $rname = $repo[name];
      $rawCreateDate = date_parse($repo[created_at]);
      $rawPushDate = date_parse($repo[pushed_at]);
      $rdate = $rawCreateDate[year] . "-" . $rawCreateDate[month] . "-" . $rawCreateDate[day]; 
      $rpush = $rawPushDate[year] . "-" . $rawPushDate[month] . "-" . $rawPushDate[day];
      $rdesc = $repo[description];
      $rstars = $repo[stargazers_count];
      
      $insertTemplate = 
      ("INSERT INTO php_repos(repo_id,name,created_date,last_push,description,stars)VALUES('%s', '%s', '%s', '%s', '%s', %d);");
      
      //sanatize values and write to table
      $insertQuery = sprintf(
        $insertTemplate,
        mysqli_real_escape_string($conn, $rid),
        mysqli_real_escape_string($conn, $rname),
        mysqli_real_escape_string($conn, $rdate),
        mysqli_real_escape_string($conn, $rpush),
        mysqli_real_escape_string($conn, $rdesc),
        mysqli_real_escape_string($conn, $rstars)
      );
      
     $conn->query($insertQuery);
    }
    //END LOOP
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>PHP Repos</title>
  <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="./main.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.css">
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.js"></script>
  <script>
      $(document).ready( function () {
        $('#table_id').DataTable();
      } );
  </script>
</head>
<body>
  <h1>Github Allstars - PHP edition</h1>

  <table id="table_id" class="display">
    <thead>
        <tr>
            <th>Github ID</th>
            <th>Repo Name</th>
            <th>Description</th>
            <th>Created Date</th>
            <th>Last Push</th>
            <th>Stars</th>
        </tr>
    </thead>
    <tbody>
      <?php 
        $results = $conn->query("SELECT * FROM php_repos;");
        foreach($results as $row){
        ?>
          <tr>
            <td><?php echo $row[repo_id] ?></td>
            <td><?php echo $row[name] ?></td>
            <td><?php echo $row[description] ?></td>
            <td><?php echo $row[created_date] ?></td>
            <td><?php echo $row[last_push] ?></td>
            <td><?php echo $row[stars] ?></td>
          </tr>
       <?php
        }
      ?>
    </tbody>
</table>

</body>
</html>