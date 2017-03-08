<!doctype html>
<html lang="">
<head>
    <meta name="viewport" content="initial-scale=1"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="$description">
    <meta content="width=device-width, initial-scale=1, user-scalable=no" name="viewport">

    {{! charset("utf-8") !}}
    {{! pageTitle($title) !}}

    <link rel="icon" type="image/png" href="/favicon-32x32.png?v=1.0" sizes="32x32">
    <link rel="icon" type="image/png" href="/favicon-16x16.png?v=1.0" sizes="16x16">
    <link rel="shortcut icon" type="image/x-icon" href="/themes/images/favicon.ico">

    <!-- Google icon -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Bootstrap css -->
    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css">
    <!-- Propeller css -->
    <link rel="stylesheet" type="text/css" href="/assets/css/propeller.min.css">

    <!-- Propeller date time picker css-->
    <link rel="stylesheet" type="text/css" href="/components/datetimepicker/css/bootstrap-datetimepicker.css"/>
    <link rel="stylesheet" type="text/css" href="/components/datetimepicker/css/pmd-datetimepicker.css"/>

    <!-- Select2 css-->
    <link rel="stylesheet" type="text/css" href="/components/select2/css/select2.min.css"/>
    <link rel="stylesheet" type="text/css" href="/components/select2/css/select2-bootstrap.css"/>
    <!-- Propeller select2 css-->
    <link rel="stylesheet" type="text/css" href="/components/select2/css/pmd-select2.css"/>

    <!-- Propeller theme css-->
    <link rel="stylesheet" type="text/css" href="/themes/css/propeller-theme.css"/>

    <!-- Propeller admin theme css-->
    <link rel="stylesheet" type="text/css" href="/themes/css/propeller-admin.css">

</head>

<body>

<!--content area start-->
<div id="content" class="pmd-content inner-page">

    <!--tab start-->
    <div class="container-fluid full-width-container">


        <main class="main">
            <% show("content") %>
        </main>


    </div><!-- tab end -->

</div><!-- content area end -->


<!-- Scripts Starts -->
<script src="/assets/js/jquery-1.12.2.min.js"></script>
<script src="/assets/js/bootstrap.min.js"></script>
<script src="/assets/js/propeller.min.js"></script>
<!-- Scripts Ends -->

</body>
</html>