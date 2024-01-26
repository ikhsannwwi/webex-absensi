@extends('administrator.layouts.main')

@section('content')
    <!-- Basic Tables start -->
    <section class="section">
        <div class="card">
            <div class="card-header">
                Settings
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.settings') }}">Menu Setting</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Administrator</li>
                    </ol>
                </nav>
            </div>
            <div class="card-body">
                <div class="row">
                    @if (isallowed('settings', 'admin_general'))
                        <div class="col-lg-6 col-12">
                            <div class="card card-large-icons">
                                <div class="card-icon bg-main-background-color text-white">
                                    <i class="fas fa-bars"></i>
                                </div>
                                <div class="card-body">
                                    <h4>General</h4>
                                    <p>General Settings.</p>
                                    <a href="{{ route('admin.settings.admin.general') }}" class="card-cta">Change Setting <i
                                            class="fas fa-chevron-right"></i></a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (isallowed('settings', 'admin_smtp'))
                        <div class="col-lg-6 col-12">
                            <div class="card card-large-icons">
                                <div class="card-icon bg-main-background-color text-white">
                                    <i class="fas fa-bars"></i>
                                </div>
                                <div class="card-body">
                                    <h4>SMTP</h4>
                                    <p>SMTP Settings.</p>
                                    <a href="{{ route('admin.settings.admin.smtp') }}" class="card-cta">Change Setting <i
                                            class="fas fa-chevron-right"></i></a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
