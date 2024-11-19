<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up • HobbyHub</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>

<div id="container">
    <div id="logo">
    <h1>HobbyHub</h1>
    </div>
    <div id="form_login">
    <form method="POST" action="register.php">
        <input type="text" id="username" name="username" placeholder="Email"  ><br>
        <input type="text" id="fullname" name="fullname" placeholder="Full Name"  ><br>
        <input type="text" id="username" name="username" placeholder="Username" ><br>
        <input type="password" id="password" name="password" placeholder="Password" ><br>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" ><br>
        <button class="button-10" role="button">Sign up</button>
    </form>
    </div>


</div>
<div id="container_register">
    <p>Have an account? 
<a href="login.php">Log in</a>        
</p>
</div>

   
   
</body>
</html>