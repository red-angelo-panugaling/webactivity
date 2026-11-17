<!DOCTYPE html>
<html lang="en">
    @section('title', 'Charts')
    @include('partials.header')

    <body class="sb-nav-fixed">
        @include('partials.navbar')
        <div id="layoutSidenav">
            @include('partials.sidebar')
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Charts</h1>
                        <ol class="breadcrumb mb-4"><li class="breadcrumb-item active">Charts</li></ol>
                        <div class="row">
                            <div class="col-xl-6"><div class="card mb-4"><div class="card-header"><i class="fas fa-chart-area me-1"></i> Area Chart Example</div><div class="card-body"><canvas id="myAreaChart" width="100%" height="40"></canvas></div></div></div>
                            <div class="col-xl-6"><div class="card mb-4"><div class="card-header"><i class="fas fa-chart-bar me-1"></i> Bar Chart Example</div><div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas></div></div></div>
                            <div class="col-xl-6"><div class="card mb-4"><div class="card-header"><i class="fas fa-chart-pie me-1"></i> Pie Chart Example</div><div class="card-body"><canvas id="myPieChart" width="100%" height="40"></canvas></div></div></div>
                        </div>
                    </div>
                </main>
                @include('partials.footer')
            </div>
        </div>
        @include('partials.scripts')
    </body>
</html>
