<?php

session_start();
$db = mysqli_connect('mars.cs.qc.cuny.edu' , 'yaya2379' , '23822379' , 'yaya2379') or die("could not connect to database" ) ;
$urloriginal = $_POST['urloriginal'] ;
$urlshortened = $_POST['urlshortened'] ;
$email = $_SESSION['email'] ;

if(isset($_POST['save']) )
{
	$user_check_query = "Select * from URL where originalURL = '$urloriginal' LIMIT 1" ;
	$results = mysqli_query( $db , $user_check_query ) ;
	$urlexists = mysqli_fetch_assoc($results) ;
	if($urlexists)
	{
		if($urlexists["originalURL"] == $urloriginal)
		{
			echo "The url is already stored, it will be open...";
			$query = "Update URL set Hit = Hit+1 where originalURL = '$urloriginal' " ;
			mysqli_query($db , $query ) ;
			header("Refresh:2; url=$urloriginal");
		}
	}
	else
	{
		$errors = array() ;	

		if( empty($urloriginal))
		{
			array_push($errors , "Original url is required." ) ;
		}
		if( empty($urlshortened))
		{
			array_push($errors , "Shortened url is required." ) ;
		}
		if( count($errors) == 0 )
		{
			$query = "Insert into URL (Email, originalURL, shortenedURL, Date, Hit) values ( '$email' , '$urloriginal' , '$urlshortened', now(), 1)" ;

			mysqli_query($db , $query ) ;
			echo "success";
			mysqli_close($db);
			header("Refresh:2; url=url_shortener.html");
		}
	}
	mysqli_close($conn);
}

if(isset($_POST['report']) )
{
	$query = "Select * from URL where Email = '$email'" ;
	$result = mysqli_query( $db , $query ) ;
	if(! $result){
		echo("Can't get your report.");
	}
	else{
		echo "<h2>Report</h2>";
		echo "<table border='1'><tr><td>OriginalURL</td><td>ShortenedURL</td><td>Date</td><td>Hits</td></tr>";
		while ($row = mysqli_fetch_assoc($result)){
			$link = $row['originalURL'];
			echo "<tr><td> <a href='$link' title=''>$link</a> </td> ".
				"<td> {$row['shortenedURL']}</td> ".
				"<td> {$row['Date']}</td> ".
				"<td> {$row['Hit']}</td> ".
				"</tr>";
		}
		echo '</table>';
	}
	mysqli_close($conn);
}



?>
<?php if (is_countable($errors) && count($errors) > 0) : ?>
    <div>
    <?php foreach($errors as $error) : ?>
    <p><?php echo $error ?></p>
    <?php endforeach ?>
    <?php header("Refresh:2; url= url_shortener.html"); ?>
    </div>
    
    <?php endif ?>