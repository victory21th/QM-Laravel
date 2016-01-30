<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="/favicon.png">
    <title>
        @section('title')
            {{ SITE_NAME }}
        @show
    </title>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
    {{ HTML::style('/assets/metronic/global/plugins/font-awesome/css/font-awesome.min.css') }}
    {{ HTML::style('/assets/metronic/global/plugins/simple-line-icons/simple-line-icons.min.css') }}
    {{ HTML::style('/assets/metronic/global/plugins/bootstrap/css/bootstrap.min.css') }}
    {{ HTML::style('/assets/metronic/global/plugins/uniform/css/uniform.default.css') }}
    {{ HTML::style('/assets/metronic/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL STYLES -->
    {{ HTML::style('/assets/metronic/global/plugins/select2/select2.css') }}
    {{ HTML::style('/assets/metronic/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}
    <!-- END PAGE LEVEL STYLES -->

    <!-- BEGIN THEME STYLES -->
    {{ HTML::style('/assets/metronic/global/css/components.css') }}
    {{ HTML::style('/assets/metronic/global/css/plugins.css') }}
    {{ HTML::style('/assets/metronic/admin/layout/css/layout.css') }}
    {{ HTML::style('/assets/metronic/admin/layout/css/themes/blue.css') }}
    {{ HTML::style('/assets/metronic/admin/layout/css/custom.css') }}
    <!-- END THEME STYLES -->
    
    {{ HTML::style('/assets/css/style_bootstrap.css') }}
    {{ HTML::style('/assets/css/style_common.css') }}
    @yield('styles')
    @yield('custom-styles')
    
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="page-header-fixed page-quick-sidebar-over-content">    
    @yield('header')
    
    @yield('body')
    
    @yield('footer')
</body>
    {{ HTML::script('//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js') }}
    {{ HTML::script('//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js') }}
    
    <!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
    <!-- BEGIN CORE PLUGINS -->
    <!--[if lt IE 9]>
    {{ HTML::script('/assets/metronic/global/plugins/respond.min.js') }}
    {{ HTML::script('/assets/metronic/global/plugins/excanvas.min.js') }}
    <![endif]-->
    {{ HTML::script('/assets/metronic/global/plugins/jquery-1.11.0.min.js') }}
    {{ HTML::script('/assets/metronic/global/plugins/jquery-migrate-1.2.1.min.js') }}
    <!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
    {{ HTML::script('/assets/metronic/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js') }}
    {{ HTML::script('/assets/metronic/global/plugins/bootstrap/js/bootstrap.min.js') }}
    {{ HTML::script('/assets/metronic/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js') }}
    {{ HTML::script('/assets/metronic/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}
    {{ HTML::script('/assets/metronic/global/plugins/jquery.blockui.min.js') }}    
    {{ HTML::script('/assets/metronic/global/plugins/jquery.cokie.min.js') }}
    {{ HTML::script('/assets/metronic/global/plugins/uniform/jquery.uniform.min.js') }}
    {{ HTML::script('/assets/metronic/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}
    <!-- END CORE PLUGINS -->
    
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    {{ HTML::script('/assets/metronic/global/plugins/select2/select2.min.js') }}
    {{ HTML::script('/assets/metronic/global/plugins/datatables/media/js/jquery.dataTables.min.js') }}
    {{ HTML::script('/assets/metronic/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') }}
    <!-- END PAGE LEVEL PLUGINS -->
    
    
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    {{ HTML::script('/assets/metronic/global/scripts/metronic.js') }}
    {{ HTML::script('/assets/metronic/admin/layout/scripts/layout.js') }}
    {{ HTML::script('/assets/metronic/admin/layout/scripts/quick-sidebar.js') }}
    {{ HTML::script('/assets/metronic/admin/pages/scripts/table-managed.js') }}
    <!-- END PAGE LEVEL SCRIPTS -->
        
    @yield('scripts')
    @yield('custom-scripts')    
    
    <script>
    jQuery(document).ready(function() {       
        Metronic.init(); // init metronic core components
        Layout.init(); // init current layout
        QuickSidebar.init() // init quick sidebar
    });
    </script>
</html>
