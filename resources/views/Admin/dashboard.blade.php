<!DOCTYPE html>
<html lang="en">
    @include('partials.header')

    <body class="sb-nav-fixed">
        @include('partials.navbar')

        <div id="layoutSidenav">
            @include('partials.sidebar')

            <div id="layoutSidenav_content">
                <main>
                    @include('partials.analytics')
                </main>

                @include('partials.footer')
            </div>
        </div>

        @include('partials.scripts')
    </body>
</html>
