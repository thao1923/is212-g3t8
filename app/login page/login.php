<?php
require_once 'model/common.php';
?>

<!-- Login page html -->
<html>
    <head>
        <title>Login</title>
        
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">


        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <link rel="stylesheet" type="text/css" href="signin.css">

    </head>
    <body class="text-center" >
        <!-- <h1>Login</h1> -->
        
        <form class="form-signin" method='POST' action='process_login.php' style='background-color:rgba(131, 197, 201, 0.1)'>
       <p style='font-family:Segoe UI; font-size:25px' > Merlion University </p>
            <img class="mb-4" src="loginpic.png" alt="" width="100" height="100">
         
            <h1 class="h3 mb-3 font-weight-normal">BIOS Login</h1>
            
            <?php
                    if (isset($_SESSION['error'])){
                        $msg = $_SESSION['error'];
                        echo "<p style = 'color:red;'>$msg</p>";
                        unset ($_SESSION['error']);
                        }
                            
            ?>
            <label for="username" class="sr-only">Username</label>
            <input type="username" name="username" id="username" class="form-control" placeholder="Username" required autofocus>
                
            <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
            <!-- <button class="btn btn-lg btn-primary btn-block" type="submit" name = 'Login'>Sign in</button> -->
            <button class='myButton' type="submit" name = 'Login'>Sign In</button>

                <!-- <table>
                    <tr>
                        <td>Username</td>
                        <td>
                            <input name='username' />
                        </td>
                    </tr>
                    <tr>
                        <td>Password</td>
                        <td>
                            <input name='password' type='password' />
                        </td>
                    </tr>
                    <tr>
                        <td colspan='2'>
                            <input name='Login' type='submit' />
                        </td>
                    </tr>
                </table>              -->
        </form>

  
        
    </body>
</html>