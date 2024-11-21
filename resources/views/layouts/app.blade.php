<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ trans('panel.site_title') }}</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://unpkg.com/@coreui/coreui@3.2/dist/css/coreui.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css"
        rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />

    @yield('styles')
    <style>
        .bg-info {
            background-color: #81b940 !important;
        }

        .bg-danger {
            background-color: #ff7313 !important;
        }

        .c-app {
            color: #42b345;
            background-color: #ebedef;
            --color: #2eb85c;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-direction: row;
            flex-direction: row;
            min-height: 100vh;
        }

        .c-sidebar .c-active.c-sidebar-nav-dropdown-toggle .c-sidebar-nav-icon,
        .c-sidebar .c-sidebar-nav-link.c-active .c-sidebar-nav-icon {
            color: #495057;
        }

        .c-sidebar {
            position: relative;
            display: -ms-flexbox;
            display: flex;
            -ms-flex: 0 0 256px;
            flex: 0 0 256px;
            -ms-flex-direction: column;
            flex-direction: column;

            -ms-flex-order: -1;
            order: -1;
            width: 256px;
            padding: 0;
            box-shadow: none;
            color: #fff;
            background: #a9dd94;
            transition: box-shadow .3s .15s, margin-left .3s, margin-right .3s, width .3s, z-index 0s ease .3s, -webkit-transform .3s;
            transition: box-shadow .3s .15s, transform .3s, margin-left .3s, margin-right .3s, width .3s, z-index 0s ease .3s;
            transition: box-shadow .3s .15s, transform .3s, margin-left .3s, margin-right .3s, width .3s, z-index 0s ease .3s, -webkit-transform .3s;
        }

        .c-sidebar .c-sidebar-nav-dropdown-toggle,
        .c-sidebar .c-sidebar-nav-link {
            color: #212529;
            background: 0 0;
        }

        .c-sidebar .c-active.c-sidebar-nav-dropdown-toggle,
        .c-sidebar .c-sidebar-nav-link.c-active {
            color: #212529;
            background: rgba(255, 255, 255, .05);
        }

        .c-sidebar .c-sidebar-nav-dropdown-toggle .c-sidebar-nav-icon,
        .c-sidebar .c-sidebar-nav-link .c-sidebar-nav-icon {
            color: #212529;
        }

        .btn-primary {
            color: #fff;
            background-color: #a9dd94;
            border-color: #a9dd94;
        }

        .c-app,
        :root {
            --primary: #a9dd94;
            --secondary: #cf9450;
            --success: #2eb85c;
            --info: #39f;
            --warning: #f9b115;
            --danger: #e55353;
            --light: #ebedef;
            --dark: #636f83;
        }

        .form-control {
            display: block;
            width: 100%;
            background-clip: padding-box;
            color: #636f83;
            background-color: #a9dd94;
            border-color: #2eb85c;
    </style>
</head>

<body class="header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden login-page">
    <div class="c-app flex-row align-items-center">
        <div class="container">
            @yield('content')
        </div>
    </div>
    @yield('scripts')
</body>

</html>
