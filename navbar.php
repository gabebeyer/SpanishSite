<?php 
require("html_imports.php");
?>

<nav class="navbar navbar-default">
	  <div class="container-fluid">
	  	<div style="padding:0 75px">
	    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	      <ul class="nav navbar-nav">
	      	<!-- SUBJECT TO CHANGE-->
	      	<!-- configured for MAMP -->
	      	<!-- Always use absolute paths -->
	        <li><a href="http://localhost:8888/SpanishSite/login_html.php">Log In</a></li>
	       
	        <li><a href="http://localhost:8888/SpanishSite/logout.php">Log Out</a></li>
	       
	        <li><a href="#">Sign Up</a></li>
	      
	      </ul>
	    
	  		<ul class="nav navbar-nav navbar-right">
       			
       			<?php 
	        		if ( !empty($_SESSION['CurrentUser']))  {
	        			$user = $_SESSION['CurrentUser'];
	        			echo " <a class='navbar-brand' href='#''>$user</a>";
	        		}
	         	?>
     		</ul>
    
	    </div><!-- /.navbar-collapse -->
	  </div><!-- /.container-fluid -->
</nav>
