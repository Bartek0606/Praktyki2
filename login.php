<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login • HobbyHub</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

<div id="container">
    <div id="logo">
    <h1>HobbyHub</h1>
    </div>
    <div id="form_login">
    <form method="POST" action="login.php">
        <input type="text" id="username" name="username" placeholder="Email"  ><br><br>
        
        <input type="password" id="password" name="password" placeholder="Password" ><br><br>
        <button class="button-10" role="button">Log in</button>
    </form>
    </div>


</div>
<div id="container_register">
    <p>Don't have an account? 
<a href="register.php">Sign up</a>        
</p>
</div>

   
   
</body>
</html>