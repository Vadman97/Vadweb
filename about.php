<?php
require_once("htmlHead.php");
require_once("util.php");
?>
<head>
	<title>Vadweb About</title>
	<link href="/resource/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="/resource/bootstrap/css/carousel.css" rel="stylesheet" type="text/css">
	<?php
		logGenericPageView("about.php");
	?>
</head>

<body>
	<div class="">
		<div class="container">

			<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
				<div class="container">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="/">Vadweb</a>
					</div>
					<div class="navbar-collapse collapse">
						<ul class="nav navbar-nav">
							<li><a style="color:#FFF" href="/register.php">Register</a></li>
							<li><a style="color:#FFF" href="/files.php">File Uploads</a></li>
							<li><a style="color:#FFF" href="/account.php">Account Settings</a></li>
						</ul>
						<?php printNavBarForms(); ?>
					</div>
				</div>
			</div>

		</div>
	</div>


    <!-- Carousel
    ================================================== -->
    <div id="myCarousel" class="carousel slide" data-ride="carousel" style="margin-top:50px">
    	<!-- Indicators -->
    	<ol class="carousel-indicators">
    		<li data-target="#myCarousel" data-slide-to="0" class=""></li>
    		<li data-target="#myCarousel" data-slide-to="1" class="active"></li>
    		<li data-target="#myCarousel" data-slide-to="2"></li>
    	</ol>
    	<div class="carousel-inner">
    		<div class="item">
    			<img src="/file.php?name=IMG_1836.jpg" alt="tahoe lake vadweb motivational ocean sky mountain beautiful">
    			<div class="container">
    				<div class="carousel-caption">
    					<h1>The author.</h1>
    					<p>Hello! Thanks for stopping by to read this blurb about me, Vadim. I'm just a high school student with a passion for computer science, from robots to website building.</p>
    					<p>This project is my start in webdesign, as I found robotics interesting in tangible electrical engineering and programming experience, but wanted a more theoretical programming project that could be used by many other than myself.</p>

    				</div>
    			</div>
    		</div>
    		<div class="item active">
    			<img src="/file.php?name=vadwebCode.png" alt="vadweb code picture">
    			<div class="container">
    				<div class="carousel-caption">
    					<h1>About the Vadweb project.</h1>
    					<p>The Vadweb project was meant as a way for people to interact and share images in a secure setting, to allow friends to share ideas with highly customizeable permissions
    					settings.</p>
    					<p>Vadweb meant to be an upgrade from websites such as <code>Facebook.com</code> or <code>Imgur.com</code> to aid productivity while providing the security and customizeability needed,
    					with only one author dedicated to earning the trust of his users.</p>
    				</div>
    			</div>
    		</div>
    		<div class="item">
    			<img src="/file.php?name=vadwebFTP.png" alt="ftp client filezilla mysql">
    			<div class="container">
    				<div class="carousel-caption" style="color:black">
    					<h1>The planning, design, technology, and motivation.</h1>
    					<p>Vadweb initially arose from a practice PHP website I was using to learn the new programming language. Soon I learned how to integrate SQL, Javascript and HTML into my project.</p>
    					<p>The website was also an opportunity for me to learn about website hosting and server technologies, from setting up my own Linux machine to configuring Apache and MySQL servers.</p>
    				</div>
    			</div>
    		</div>
    	</div>
    	<a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
    	<a class="right carousel-control" href="#myCarousel" role="button" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
    </div><!-- /.carousel -->



    <!-- Marketing messaging and featurettes
    ================================================== -->
    <!-- Wrap the rest of the page in another container to center all the content. -->

    <div class="container marketing">

    	<!-- Three columns of text below the carousel -->
    	<div class="row">
    		<div class="col-lg-4">
    			<img class="img-circle" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="Generic placeholder image" style="width: 140px; height: 140px;">
    			<h2>V2 - Complete Redesign</h2>
    			<p>The current website you are seeing is a complete redesign of the initial model that was created in 2013. The summer of 2014, I made the decision to restart the project from scratch to emphasize performance and scalability, the significant challenges I encountered with the previous design: problems that arose when over 1000 files were uploaded to the system.</p>
    		</div><!-- /.col-lg-4 -->
    		<div class="col-lg-4">
    			<img class="img-circle" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="Generic placeholder image" style="width: 140px; height: 140px;">
    			<h2>A Project in Progress</h2>
    			<p>Unfortunately, because I decided to recently redesign the website from scratch, many of the features available in the previous version are still in development. However, I am working my hardest to present those features in a timely fashion by the end of 2014.</p>
    		</div><!-- /.col-lg-4 -->
    		<div class="col-lg-4">
    			<img class="img-circle" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="Generic placeholder image" style="width: 140px; height: 140px;">
    			<h2>Overcoming Challenges</h2>
    			<p>One significant challenge with starting this project was figuring out howo to host my own website. While I knew that buying hosting would be easier and already set up for me, I many days before I started actually programming trying to set up the server, trying to get the different services working as I intended, setting up a PHP, MySQL, and Mail servers. Not only did I get a more complete experience of webdesigning, but I gained useful skills in server configuration and the back end of how website development works. And of course, it was free as the server is still hosted in the comfort of my home.</p>
    		</div><!-- /.col-lg-4 -->
    	</div><!-- /.row -->


    	<!-- START THE FEATURETTES -->
    	<div hidden>

	    	<hr class="featurette-divider">

	    	<div class="row featurette">
	    		<div class="col-md-7">
	    			<h2 class="featurette-heading">First featurette heading. <span class="text-muted">It'll blow your mind.</span></h2>
	    			<p class="lead">Donec ullamcorper nulla non metus auctor fringilla. Vestibulum id ligula porta felis euismod semper. Praesent commodo cursus magna, vel scelerisque nisl consectetur. Fusce dapibus, tellus ac cursus commodo.</p>
	    		</div>
	    		<div class="col-md-5">
	    			<img class="featurette-image img-responsive" data-src="holder.js/500x500/auto" alt="500x500" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI1MDAiIGhlaWdodD0iNTAwIj48cmVjdCB3aWR0aD0iNTAwIiBoZWlnaHQ9IjUwMCIgZmlsbD0iI2VlZSIvPjx0ZXh0IHRleHQtYW5jaG9yPSJtaWRkbGUiIHg9IjI1MCIgeT0iMjUwIiBzdHlsZT0iZmlsbDojYWFhO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1zaXplOjMxcHg7Zm9udC1mYW1pbHk6QXJpYWwsSGVsdmV0aWNhLHNhbnMtc2VyaWY7ZG9taW5hbnQtYmFzZWxpbmU6Y2VudHJhbCI+NTAweDUwMDwvdGV4dD48L3N2Zz4=">
	    		</div>
	    	</div>

	    	<hr class="featurette-divider">

	    	<div class="row featurette">
	    		<div class="col-md-5">
	    			<img class="featurette-image img-responsive" data-src="holder.js/500x500/auto" alt="500x500" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI1MDAiIGhlaWdodD0iNTAwIj48cmVjdCB3aWR0aD0iNTAwIiBoZWlnaHQ9IjUwMCIgZmlsbD0iI2VlZSIvPjx0ZXh0IHRleHQtYW5jaG9yPSJtaWRkbGUiIHg9IjI1MCIgeT0iMjUwIiBzdHlsZT0iZmlsbDojYWFhO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1zaXplOjMxcHg7Zm9udC1mYW1pbHk6QXJpYWwsSGVsdmV0aWNhLHNhbnMtc2VyaWY7ZG9taW5hbnQtYmFzZWxpbmU6Y2VudHJhbCI+NTAweDUwMDwvdGV4dD48L3N2Zz4=">
	    		</div>
	    		<div class="col-md-7">
	    			<h2 class="featurette-heading">Oh yeah, it's that good. <span class="text-muted">See for yourself.</span></h2>
	    			<p class="lead">Donec ullamcorper nulla non metus auctor fringilla. Vestibulum id ligula porta felis euismod semper. Praesent commodo cursus magna, vel scelerisque nisl consectetur. Fusce dapibus, tellus ac cursus commodo.</p>
	    		</div>
	    	</div>

	    	<hr class="featurette-divider">

	    	<div class="row featurette">
	    		<div class="col-md-7">
	    			<h2 class="featurette-heading">And lastly, this one. <span class="text-muted">Checkmate.</span></h2>
	    			<p class="lead">Donec ullamcorper nulla non metus auctor fringilla. Vestibulum id ligula porta felis euismod semper. Praesent commodo cursus magna, vel scelerisque nisl consectetur. Fusce dapibus, tellus ac cursus commodo.</p>
	    		</div>
	    		<div class="col-md-5">
	    			<img class="featurette-image img-responsive" data-src="holder.js/500x500/auto" alt="500x500" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI1MDAiIGhlaWdodD0iNTAwIj48cmVjdCB3aWR0aD0iNTAwIiBoZWlnaHQ9IjUwMCIgZmlsbD0iI2VlZSIvPjx0ZXh0IHRleHQtYW5jaG9yPSJtaWRkbGUiIHg9IjI1MCIgeT0iMjUwIiBzdHlsZT0iZmlsbDojYWFhO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1zaXplOjMxcHg7Zm9udC1mYW1pbHk6QXJpYWwsSGVsdmV0aWNhLHNhbnMtc2VyaWY7ZG9taW5hbnQtYmFzZWxpbmU6Y2VudHJhbCI+NTAweDUwMDwvdGV4dD48L3N2Zz4=">
	    		</div>
	    	</div>

	    	<hr class="featurette-divider">

	    	<!-- /END THE FEATURETTES -->
    	</div>

    	<!-- FOOTER -->
    	<footer>
    		<p class="pull-right"><a href="#">Back to top</a></p>
    		<p>Please be aware that some features are in beta testing or in development, and may not be available for all users</p>
  			<p><b>© Vadweb 2014-2015</b></p>
    	</footer>

    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/resource/jquery/jquery-2.1.1.min.js"></script>
    <script src="/resource/bootstrap/js/bootstrap.js"></script>

</body>