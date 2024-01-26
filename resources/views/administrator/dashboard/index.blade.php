@extends('administrator.layouts.main')

@section('content')
    <div class="page-heading">
        <h3>Profile Statistics<a href="javascript:void(0)" class="btn btn-primary" style="float: right;" id="triggerRefresh"><i
                    class="fas fa-sync-alt"></i></a></h3>
    </div>
    <div class="page-content">
        <section class="row" id="sectionPage">
            <div class="col-12 col-lg-9">
                <div class="row">
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                        <div class="stats-icon purple mb-2">
                                            <i class="iconly-boldShow"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Total Visit</h6>
                                        <h6 class="font-extrabold mb-0">{{ $Statistic->count() }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                        <div class="stats-icon blue mb-2">
                                            <i class="iconly-boldProfile"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Followers</h6>
                                        <h6 class="font-extrabold mb-0">183.000</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                        <div class="stats-icon green mb-2">
                                            <i class="iconly-boldAdd-User"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Following</h6>
                                        <h6 class="font-extrabold mb-0">80.000</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-4 py-4-5">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                        <div class="stats-icon red mb-2">
                                            <i class="iconly-boldBookmark"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">Saved Post</h6>
                                        <h6 class="font-extrabold mb-0">112</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Visit</h4>
                            </div>
                            <div class="card-body">
                                <div id="chart-profile-visit"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-3">
                <div class="card">
                    <div class="card-header">
                        <h4>Recent Activities</h4>
                    </div>
                    <div class="card-content pb-4">
                        @php
                            $counter = 0;
                            $no = 1;
                        @endphp
                        @foreach ($Statistic->take(5) as $row)
                        @php
                            $avatar = 'templateAdmin/assets/images/faces/' . $no . '.jpg';
                        @endphp
                            <div class="recent-message d-flex px-4 py-3">
                                <div class="avatar avatar-lg">
                                    <img src="{{ asset($avatar) }}">
                                </div>
                                <div class="name ms-4">
                                    @php
                                        $no = ($no % 4) + 1;

                                        $getLocation = Stevebauman\Location\Facades\Location::get($row->ip_address);

                                        if ($getLocation) {
                                            $location = $getLocation->cityName . '-' . $getLocation->countryName;
                                        } else {
                                            $location = $row->ip_address;
                                        }

                                    @endphp
                                    <h5 class="mb-1">Hank Schrader</h5>
                                    <span>{{ Carbon\Carbon::parse($row->visit_time)->diffForHumans() }}</span>
                                    <h6 class="text-muted mb-0">Telah mengunjungi page @if ($row->url === '')
                                            home
                                        @else
                                            {{ $row->url }}
                                        @endif di browser
                                        {{ $row->browser }} menggunakan platform {{ $row->platform }}</h6>
                                </div>
                            </div>
                        @endforeach

                        <div class="px-4">
                            <a href="{{route('admin.statistic')}}" class='btn btn-block btn-xl btn-outline-primary font-bold mt-3'>View All</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('js')
    <script src="{{ template_administrator('assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
    {{-- <script src="{{template_administrator('assets/js/pages/dashboard.js')}}"></script> --}}

    <script type="text/javascript">
        $(document).ready(function() {
            let canClick = true;

            $('#triggerRefresh').on('click', function() {
                if (canClick) {
                    let another = this;
                    $(another).find('.fas').addClass('fa-spin');

                    $.ajax({
                        url: "{{ route('admin.dashboard.fetchData') }}",
                        success: function(data) {
                            $('#sectionPage').html(data);
                            $(another).find('.fas').removeClass('fa-spin');
                        },
                    });

                    canClick = false;

                    setTimeout(function() {
                        canClick = true;
                        $(another).removeClass('btn-danger');
                    }, 60000); // 1 minute in milliseconds
                } else {
                    $(this).addClass('btn-danger');
                }
            });

            var optionsProfileVisit = {
                annotations: {
                    position: 'back'
                },
                dataLabels: {
                    enabled: false
                },
                chart: {
                    type: 'bar',
                    height: 300
                },
                fill: {
                    opacity: 1
                },
                plotOptions: {},
                series: [{
                    name: 'visit',
                    data: @json($chartDataMonthly)
                }],
                colors: '#435ebe',
                xaxis: {
                    categories: @json($chartLabelsMonthly),
                },
            }
            var chartProfileVisit = new ApexCharts(document.querySelector("#chart-profile-visit"),
                optionsProfileVisit);
            chartProfileVisit.render();

        });
    </script>
@endpush
