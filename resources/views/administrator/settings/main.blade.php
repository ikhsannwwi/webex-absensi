@extends('administrator.layouts.main')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Layout & Basic with Icons -->
        <div class="row">
            <!-- Basic Layout -->
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-header">
                        Settings
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Menu Setting</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if (isallowed('settings', 'frontpage'))
                                <div class="col-lg-6 col-12">
                                    <div class="card card-large-icons">
                                        <div class="card-icon bg-main-background-color text-white">
                                            <i class="fas fa-cog"></i>
                                        </div>
                                        <div class="card-body">
                                            <h4>Frontpage</h4>
                                            <p>Settings Frontpage.</p>
                                            <a href="{{ route('admin.settings.frontpage') }}" class="card-cta">Change
                                                Setting <i class="fas fa-chevron-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if (isallowed('settings', 'admin'))
                                <div class="col-lg-6 col-12">
                                    <div class="card card-large-icons">
                                        <div class="card-icon bg-main-background-color text-white">
                                            <i class="fas fa-bars"></i>
                                        </div>
                                        <div class="card-body">
                                            <h4>Adminsitrator</h4>
                                            <p>Settings Adminsitrator.</p>
                                            <a href="{{ route('admin.settings.admin') }}" class="card-cta">Change Setting <i
                                                    class="fas fa-chevron-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
