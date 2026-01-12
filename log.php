<?php
session_start();
$email=$_POST['email'] ;
$pass=$_POST['pass'] ;
if($email && $pass){
	$c=0;    
$servername="sql200.infinityfree.com";
$username="if0_39853898";
$password="miniteam8";
$database="if0_39853898_team8";
    
    $sql="SELECT email, pass FROM citizen";
    $con=new mysqli($servername,$username,$password,$database); 
    $res=$con->query($sql);

    if($res->num_rows>0)
    {
        while($row=$res->fetch_assoc())
        {
            if($row['email']==$email && $row['pass']==$pass)
            {
                $c=1;
                break;
            }
        }
    }
    if ($email === "miniteam8@gmail.com" && $pass === "miniteam8") {
        $_SESSION['admin'] = true;
        header("Location: admin.php");
        exit();
    }
    if($c==1)
    {
        $_SESSION['email'] = $email;

        if (isset($_POST['remember_me'])) {
            setcookie('email_cookie', $email, time() + (86400 * 30), '/');
        }

        header('Location:home.php');
        exit;
    }
else {
    echo "<script>
            alert('❌ Invalid email or password!');
            window.location.href = 'log.php';
          </script>";
    exit();
}

$con->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CiviQ-Register-Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
	<form method="post" action="log.php">
    <div id="container" class="container">
		<!-- FORM SECTION -->
		<div class="row">
			<!-- SIGN UP -->
			<div class="col align-items-center flex-col sign-up">
				<div class="form-wrapper align-items-center">
				</div>
			</div>
			<!-- END SIGN UP -->
			<!-- SIGN IN -->
			<div class="col align-items-center flex-col sign-in">
				<div class="form-wrapper align-items-center">
					<div class="form sign-in">
                    <div class="logo-wrapper">
                    <img src="img/logo.png" alt="Website Logo">
                    </div>
						<div class="input-group">
							<i class='bx bxs-user'></i>
							<input type="email" name="email" placeholder="email">
						</div>
						<div class="input-group">
							<i class='bx bxs-lock-alt'></i>
							<input type="password" name="pass" placeholder="Password">
						</div>
						<button>
							Login 
						</button>
						<p>
							<b>
								Forgot password?
							</b>
						</p>
						<p>
							<span>
								Don't have an account?
							</span>
							<a href="reg.html" class="pointer" style="color: #86B817; font-weight: 600; text-decoration: none;">
                                 Register here
                            </a>
						</p>
					</div>
				</div>
				<div class="form-wrapper">
		
				</div>
			</div>
			<!-- END SIGN IN -->
		</div>
		</form>
		<!-- END FORM SECTION -->
		<!-- CONTENT SECTION -->
<div class="row content-row">
    <!-- SIGN IN CONTENT -->
    <div class="col align-items-center flex-col">
        <div class="text sign-in">
            <!-- Replace text with image -->
            <div class="content-logo">
                <img src="img/login1.jpg" alt="Welcome to Community Problem Reporting">
            </div>
        </div>
        <div class="img sign-in">
        </div>
    </div>
    <!-- END SIGN IN CONTENT -->

</div>
    <script>
        let container = document.getElementById('container')
setTimeout(() => {
	container.classList.add('sign-in')
}, 200)
    </script>
</body>
</html>