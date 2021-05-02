<?php
require_once "./db.php";
//echo "Connected";



if ( !empty($_POST)) {
    extract($_POST) ;

 // Launch: YYYY-MM?-DD?  preg_match('/^\d{4}-\d\d?-\d\d?$/', $launch)
    $pm = '/^\d{1,3} [A-Z]{1,3} \d{2,4}$/';
    $coloroffirst = ["gray","teal"];
 
    if(preg_match($pm,$plate)===1)
    {
    try{
         $sql = "insert into tickets (plate, description, fine) values (?,?,?)" ;
         $rs = $db->prepare($sql) ;
         $rs->execute([$plate, $description, $price]);
     } catch( PDOException $ex) {
         $errMsg = "Insert Fail" ; 
     }
   
}else {
    $errMsg = "Invalid Plate";
}

}else if (isset($_GET["delete"])) {
    $id = $_GET["delete"] ;
    try {
        $rs = $db->prepare("delete from tickets where id = :id") ;
        $rs->execute(["id" => $id]) ;
        if ( $rs->rowCount() == 0) $errMsg = "Already deleted" ;
    } catch(PDOException $ex) {
        $errMsg = "Delete Fail" ;
    }
}

try {

    $rs = $db->query("select * from tickets");
    $tickets = $rs->fetchAll(PDO::FETCH_ASSOC);
    //var_dump($test);
  } catch (PDOException $ex) {
    echo "<p>", $ex->getMessage(), "</p>";
  }
  $max=0;
  foreach ($tickets as $tck) {      
    if($max < $tck["fine"]) $max = $tck["fine"];
  }

  if($rs->rowCount() == 0)
  {
      $errMsg = "All Tickets Deleted, List is Empty";
  }
  
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Midterm2- Question 1</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
  <style>
    
  </style>
</head>

<body>


  <div class="container">
    <form action="" method="POST">
    <a href="?maxim=<?= $max ?>" class="btn-small" style="height:90px;width: 1300px;">SHOW MAX FINE</a> 
    <nav style="background-color: gray; height: 60px;">
      <div>
        <p class="left" style="font-size: 24px;margin: 0 auto;"> Tickets    </p>
      <?= "<p class='right' style='font-size: 40px;margin: 0 auto;'> " . $rs->rowCount() . "</p>"  ?>
      </div>
    </nav>
      <table>
    
        <?php foreach( $tickets as $tick) : ?>
            <tr>
                <td width="30%"><?= $tick["plate"] ?></td>
                <td width="30%"><?= $tick["description"] ?></td>
                <td width="30%"><?= $tick["fine"] ?>&#8378</td>
                <td>
                     <a href="?delete=<?= $tick["id"] ?>" class="btn-small"><i class="material-icons">delete</i></a> 
            </td>
            </tr>
            <?php endforeach ?>
            <tr>
               <td>
                  <div class="input-field">
                    <input name="plate" id="plate" type="text" class="validate">
                    <label for="title">Plate</label>
                  </div>
               </td>
               <td>
                  <div class="input-field">
                    <input name="description" id="description" type="text" class="validate">
                    <label for="description">Description</label>
                  </div>
               </td>
               <td>
               <div class="input-field">
                    <input name="price" id="price" type="text" class="validate">
                    <label for="price">Fine</label>
                  </div>
               </td>
               <td>
                  <button class="btn waves-effect waves-light" type="submit" name="action">
                    <i class="material-icons">add</i>
                  </button>
               </td>
           </tr>
       



      </table>


    </form>
    <?php
       if ( isset($errMsg)) {
           echo "<script> M.toast({html: '$errMsg', classes: 'red white-text'}) ; </script>" ;
       }
    ?>

<?php
       if ( isset($maxim)) {
           echo "<p> The max ticket is <?= $max ?> </p>" ;
       }
    ?>


  </div>







  <script>
    $(function() {

    })
  </script>
</body>

</html>
