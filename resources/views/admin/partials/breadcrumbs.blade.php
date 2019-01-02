<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <div class="btn-group pull-right">
                <ol class="breadcrumb hide-phone p-0 m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                    {{ $slot }}
                </ol>
            </div>
            <h4 class="page-title">{{ isset($title) ? $title :'' }}</h4>
        </div>
    </div>
</div>