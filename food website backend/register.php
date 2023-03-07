<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

  /*//Validates password & confirm passwords.
      if (strlen($_POST["pass"]) <= '8') {
          $passwordErr = "Your Password Must Contain At Least 8 Characters!";
      }
      elseif(!preg_match("#[0-9]+#",$pass)) {
          $passwordErr = "Your Password Must Contain At Least 1 Number!";
      }
      elseif(!preg_match("#[A-Z]+#",$pass)) {
          $passwordErr = "Your Password Must Contain At Least 1 Capital Letter!";
      }
      elseif(!preg_match("#[a-z]+#",$pass)) {
          $passwordErr = "Your Password Must Contain At Least 1 Lowercase Letter!";
      } else {
          $cpasswordErr = "Please Check You've Entered Or Confirmed Your Password!";
      }*/


   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? OR number = ?");
   $select_user->execute([$email, $number]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $message[] = 'email or number already exists!';
   }
   if($pass != $cpass){
      $message[] = 'confirm password not matched!';
   }
   if(strlen($_POST["pass"]) <= '8'){
      $message[] = 'password must be more than 8 char!';
   }
   //if (preg_match("/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])[0-9A-Za-z!-\/]{1,}$/", $Password)==1)

   if(!preg_match("#[0-9]+#",$pass)) {
      $message[] = 'password must contain numbers ';
   }
   if(!preg_match('@[A-Z]@', $_POST["pass"])) {
      $message[]= "Your Password Must Contain At Least 1 Capital Letter!";
   }

/*   $uppercase = preg_match('@[A-Z]@', $password);
$lowercase = preg_match('@[a-z]@', $password);
$number    = preg_match('@[0-9]@', $password);
*/  
else{
               $insert_user = $conn->prepare("INSERT INTO `users`(name, email, number, password) VALUES(?,?,?,?)");
               $insert_user->execute([$name, $email, $number, $cpass]);
               $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
               $select_user->execute([$email, $pass]);
               $row = $select_user->fetch(PDO::FETCH_ASSOC);
         if($select_user->rowCount() > 0){
            $_SESSION['user_id'] = $row['id'];
            header('location:home.php');
         }
      }
     }
    
   



?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<section class="form-container">

   <form action="" method="post">
      <h3>register now</h3>
      <input type="text" name="name" required placeholder="enter your name" class="box" maxlength="50">
      <input type="email" name="email" required placeholder="enter your email" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="number" name="number" required placeholder="enter your number" class="box" min="0" max="9999999999" maxlength="10">
      <input type="password" name="pass" required placeholder="enter your password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" required placeholder="confirm your password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="register now" name="submit" class="btn">
      <p>already have an account? <a href="login.php">login now</a></p>
   </form>

</section>











<?php include 'components/footer.php'; ?>







<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>