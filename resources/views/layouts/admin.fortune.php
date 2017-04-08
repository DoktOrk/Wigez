<!doctype html>
<html lang="en">
<head>
    <meta name="viewport" content="initial-scale=1"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="$description">
    <meta content="width=device-width, initial-scale=1, user-scalable=no" name="viewport">

    {{! charset("utf-8") !}}
    {{! pageTitle($title) !}}

    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">

    <!-- Google icon -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Bootstrap css -->
    <link rel="stylesheet" type="text/css" href="/admin/css/bootstrap.min.css">
    <!-- Propeller css -->
    <link rel="stylesheet" type="text/css" href="/admin/css/propeller.min.css">

    <!-- Propeller date time picker css-->
    <link rel="stylesheet" type="text/css" href="/admin/components/datetimepicker/css/bootstrap-datetimepicker.css"/>
    <link rel="stylesheet" type="text/css" href="/admin/components/datetimepicker/css/pmd-datetimepicker.css"/>

    <!-- Select2 css-->
    <link rel="stylesheet" type="text/css" href="/admin/components/select2/css/select2.min.css"/>
    <link rel="stylesheet" type="text/css" href="/admin/components/select2/css/select2-bootstrap.css"/>
    <!-- Propeller select2 css-->
    <link rel="stylesheet" type="text/css" href="/admin/components/select2/css/pmd-select2.css"/>

    <!-- Propeller theme css-->
    <link rel="stylesheet" type="text/css" href="/admin/themes/css/propeller-theme.css"/>

    <!-- Propeller admin theme css-->
    <link rel="stylesheet" type="text/css" href="/admin/themes/css/propeller-admin.css">

    <!-- Custom stlyes -->
    <link href="/admin/css/style.css" rel="stylesheet">

    <!-- Trumbowyg vendor styles -->
    <link href="/admin/vendor/trumbowyg/ui/trumbowyg.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>

<body>
    <!-- Header Starts -->
    <!--Start Nav bar -->
    <nav class="navbar navbar-inverse navbar-fixed-top pmd-navbar pmd-z-depth">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <a href="javascript:void(0);"
                   class="btn btn-sm pmd-btn-fab pmd-btn-flat pmd-ripple-effect pull-left margin-r8 pmd-sidebar-toggle"><i
                            class="material-icons">menu</i></a>
                <a href="{{! route('dashboard') !}}l" class="navbar-brand">
                    Logo
                </a>
            </div>
        </div>
    </nav><!--End Nav bar -->
    <!-- Header Ends -->

    <!-- Sidebar Starts -->
    <div class="pmd-sidebar-overlay"></div>

    <!-- Left sidebar -->
    <aside class="pmd-sidebar sidebar-default pmd-sidebar-slide-push pmd-sidebar-left pmd-sidebar-open bg-fill-darkblue sidebar-with-icons" role="navigation">
        <ul class="nav pmd-sidebar-nav">
            <li class="dropdown pmd-dropdown pmd-user-info visible-xs visible-md visible-sm visible-lg">
                <a aria-expanded="false" data-toggle="dropdown" class="btn-user dropdown-toggle media" data-sidebar="true" aria-expandedhref="javascript:void(0);">
                    <div class="media-left">
                        <img src="/admin/themes/images/user-icon.png" alt="New User">
                    </div>
                    <div class="media-body media-middle">{{ $username }}</div>
                    <div class="media-right media-middle"><i class="dic-more-vert dic"></i></div>
                </a>
                <div class="pmd-dropdown-menu-container"><div class="pmd-dropdown-menu-bg"></div><ul class="dropdown-menu">
                        <li><a href="{{! route('logout') !}}">Logout</a></li>
                    </ul></div>
            </li>
            <% if ($is_user) %>
            <li><a class="pmd-ripple-effect" href="{{! route('pages') !}}" tabindex="-1"><i class="material-icons media-left media-middle">text_format</i> <span class="media-body">Pages</span></a></li>
            <li><a class="pmd-ripple-effect" href="{{! route('categories') !}}" tabindex="-1"><i class="material-icons media-left media-middle">group_work</i> <span class="media-body">Categories</span></a></li>
            <li><a class="pmd-ripple-effect" href="{{! route('customers') !}}" tabindex="-1"><i class="material-icons media-left media-middle">person</i> <span class="media-body">Customers</span></a></li>
            <% endif %>
            <li><a class="pmd-ripple-effect" href="{{! route('files') !}}" tabindex="-1"><i class="material-icons media-left media-middle">attachment</i> <span class="media-body">Files</span></a></li>
            <% if ($is_user) %>
            <li><a class="pmd-ripple-effect" href="{{! route('downloads') !}}" tabindex="-1"><i class="material-icons media-left media-middle">file_download</i> <span class="media-body">Downloads</span></a></li>
            <% endif %>
        </ul>
    </aside><!-- End Left sidebar -->
    <!-- Sidebar Ends -->

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
    <!-- include summernote css/js-->
    <script src="/admin/js/bootstrap.min.js"></script>
    <script src="/admin/js/propeller.min.js"></script>
    <!-- Scripts Ends -->

</body>
</html>
