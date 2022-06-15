<?php

//Login users
$db = mysqli_connect('mars.cs.qc.cuny.edu' , 'yaya2379' , '23822379' , 'yaya2379') or die("could not connect to database" ) ;
$email = $_POST['email'] ;
$password = $_POST['password']  ;

if(isset($_POST['login']) )
 {
	session_start();
    $errors = array() ;

    if( empty($email))
    {
        array_push($errors , "Email is required" ) ;
    }
    if( empty($password))
    {
        array_push($errors , "Password is required" ) ;
    }
    if( count($errors) == 0 )
    {
        $password = md5($password) ;
        $query = "Select * from users where UserEmail = '$email' AND UserPassword = '$password' " ;
        $results = mysqli_query($db , $query) ;

        if( mysqli_num_rows($results) ) 
        {
            $_SESSION['email'] = $email ;
            $_SESSION['success'] = "Logged in Successfully" ;

            echo "You are now logged in. Thank you :)" ;
			echo "<br/>You will be lead to URL-shorten page soon..." ;
			header("Refresh:3; url= url_shortener.html");
        }
        else
        {
            array_push($errors , "Wrong email/Password combination. Please try again." ) ;
        }
    }
	mysqli_close($db);
 }

 else

 {
session_start() ;


$errors = array() ;

//register users

$name = $_POST['name'] ;
$confirmpassword = $_POST['confirmpassword'] ;

//form validation

if( empty($name) )
{
    array_push( $errors , "Name is required") ;
} 

if( empty($email) )
{
    array_push($errors , "email is required") ;
} 

if( empty($password))
{
    array_push($errors , "Password is required") ;
} 

if( $password != $confirmpassword )
{
    array_push($errors , "Password do not match" ) ;
}

//check database for existing user with same username
$user_check_query = "Select * from users where UserEmail = '$email' LIMIT 1" ;

$results = mysqli_query( $db , $user_check_query ) ;
$user = mysqli_fetch_assoc($results) ;


if($user)
{
    if($user["UserEmail"] === $email)
    {
        array_push($errors , "This email is already registered" ) ;
    }
}

//Register user if no errors

if( count($errors) == 0 )
{
    $password = md5($password) ; //This will encrypt password

    $query = "Insert into users (UserName , UserEmail , UserPassword ) values ( '$name' , '$email' , '$password' )" ;
    
    mysqli_query($db , $query ) ;

    $_SESSION['UserName'] = $name ;
    $_SESSION['success'] = "You are now signed Up" ;

    header( 'location: index.php' ) ;
    mysqli_close($db);
}
 
}

?>
<?php if (is_countable($errors) && count($errors) > 0) : ?>
    <div>
    <?php foreach($errors as $error) : ?>
    <p><?php echo $error ?></p>
    <?php endforeach ?>
    <!--redirect to index.php after 2 seconds-->
    <?php header("Refresh:2; url= index.php"); ?>
    </div>
    
    <?php endif ?>
    