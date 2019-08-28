<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Trang Quản Trị Thuê Trọ Nhanh</title>
        @include('admin.layout.style')
    </head>

    <body>
        <!-- Main navbar -->
        @include('admin.layout.nav')
        <!-- /main navbar -->

        <!-- Page container -->
        <div class="page-container">
            <!-- Page content -->
            <div class="page-content">
            @include('admin.layout.menu')
            <!-- Main content -->
            @yield('content2')
            <!-- /Main content -->
            </div>
            <!-- /page content -->
        </div>
        <!-- /page container -->
        @include('admin.layout.script')
    </body>
</html>
