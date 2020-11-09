<?php 

  /* Connect to DB? */

  /* DATA GET */

    /* API call here to grab most popular repos */
    /* 
      curl -H 'Accept: application/vnd.github.preview.text-match+json' https://api.github.com/search/repositories?q=language:php&sort=stars&per_page=3
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
  <title>Document</title>
</head>
<body>
  <h1>Github Allstars - PHP edition</h1>

<!-- DATA WILL GO DOWN HERE - conditional formatting, etc -->
  <?php 
    echo "hewwwwooooooo";
  ?>
  
</body>
</html>