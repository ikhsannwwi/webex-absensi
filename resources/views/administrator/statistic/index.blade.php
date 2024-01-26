@extends('administrator.layouts.main')

@section('content')
    <!-- Basic Tables start -->
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        Statistics
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Statistic</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-6">
                    </div>
                </div>
            </div>
            @include('administrator.logs.filter.main')
            <div class="card-body">
                <table class="table" id="datatable">
                    <thead>
                        <tr>
                            <th width="15px" class="text-center">
                                No
                            </th>
                            <th width="50%">Ip Address</th>
                            <th width="50%">Page</th>
                            <th width="200px">Visit Time</th>
                            <th width="100px">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </section>
    <!-- Basic Tables end -->

    @include('administrator.statistic.modal.detail')
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            var data_table = $('#datatable').DataTable({
                "oLanguage": {
                    "oPaginate": {
                        "sFirst": "<i class='ti-angle-left'></i>",
                        "sPrevious": "&#8592;",
                        "sNext": "&#8594;",
                        "sLast": "<i class='ti-angle-right'></i>"
                    }
                },
                processing: true,
                serverSide: true,
                order: [
                    [3, 'desc']
                ],
                scrollX: true, // Enable horizontal scrolling
                ajax: {
                    url: '{{ route('admin.statistic.getData') }}',
                    dataType: "JSON",
                    type: "GET",
                },
                columns: [{
                    render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                    },
                    {
                        data: 'ip_address',
                        name: 'ip_address'
                    },
                    {
                        mRender : function (data, type, row, meta) {
                            let page = '';
                            if (row.url === '') {
                                page = 'home';
                            } else {
                                page = row.url;
                            }

                            return page;
                        },
                    },
                    {
                        data: 'visit_time',
                        name: 'visit_time'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        searchable: false,
                        sortable: false,
                        class: 'text-center'
                    }
                ],
            });
        });
    </script>
@endpush
