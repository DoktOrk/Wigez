<!DOCTYPE html>
<html lang="hu">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    {{! charset("utf-8") !}}
    {{! pageTitle($title) !}}
    {{! css("http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,300,400,700") !}}

    <!-- Bootstrap Core CSS -->
    <link href="/website/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="/website/css/landing-page.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="/website/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">

    {{! css($css) !}}
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<!-- Navigation -->
<nav class="navbar navbar-default navbar-fixed-top topnav" role="navigation">
    <div class="container topnav">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Navigáció</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand topnav" href="#">Ecomp.co.hu</a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#main">Kezdőkép</a></li>
                <li><a href="#ugyvitel">Ügyvitel</a></li>
                <li><a href="#import-export">Import-Export</a></li>
                <li><a href="#tanacsadas">Tanácsadás</a></li>
                <li><a href="#szoftver">Szoftver</a></li>
                <li><a href="#kapcsolat">Kapcsolat</a></li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>

<main class="main">
    <a name="main"></a>
    <% show("content") %>
</main>


<!-- Footer -->
<footer>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <ul class="list-inline">
                    <li><a href="#main">Kezdőkép</a></li>
                    <li><a href="#ugyvitel">Ügyvitel</a></li>
                    <li><a href="#import-export">Import-Export</a></li>
                    <li><a href="#tanacsadas">Tanácsadás</a></li>
                    <li><a href="#szoftver">Szoftver</a></li>
                    <li><a href="#kapcsolat">Kapcsolat</a></li>
                </ul>
                <p class="copyright text-muted small">Copyright &copy; e-comp Kft 2017. Minden jog fenntartva</p>
            </div>
        </div>
    </div>
</footer>

<!-- jQuery -->
<script src="/website/js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="/website/js/bootstrap.min.js"></script>

</body>

</html>
