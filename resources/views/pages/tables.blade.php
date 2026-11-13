<!DOCTYPE html>
<html lang="en">
    @section('title', 'Tables')
    @include('partials.header')

    <body class="sb-nav-fixed">
        @include('partials.navbar')
        <div id="layoutSidenav">
            @include('partials.sidebar')
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Tables</h1>
                        <ol class="breadcrumb mb-4"><li class="breadcrumb-item active">Tables</li></ol>
                        <div class="card mb-4">
                            <div class="card-header"><i class="fas fa-table me-1"></i> DataTable Example</div>
                            <div class="card-body">
                                <table id="datatablesSimple" data-per-page="10">
                                    <thead><tr><th>Name</th><th>Position</th><th>Office</th><th>Age</th><th>Start date</th><th>Salary</th></tr></thead>
                                    <tfoot><tr><th>Name</th><th>Position</th><th>Office</th><th>Age</th><th>Start date</th><th>Salary</th></tr></tfoot>
                                    <tbody>
                                        @include('partials.table-demo-rows')
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>
                @include('partials.footer')
            </div>
        </div>
        @include('partials.scripts')
    </body>
</html>
