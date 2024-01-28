@extends('administrator.layouts.main')

@section('content')
    <!-- Basic Tables start -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Layout -->
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                Arsip Pembina
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a>
                                        </li>
                                        <li class="breadcrumb-item"><a href="{{ route('admin.pembina') }}">Pembina</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Arsip</li>
                                    </ol>
                                </nav>
                            </div>
                            <div class="col-6">
                                <a href="javascript:void(0)" class="btn btn-primary float-end" id="filterButton">Filter</a>
                            </div>
                        </div>
                    </div>
                    @include('administrator.pembina.filter.main')
                    <div class="card-body">
                        <table class="table" id="datatable">
                            <thead>
                                <tr>
                                    <th width="15px">No</th>
                                    <th width="200px">User Group</th>
                                    <th width="50%">Nama</th>
                                    <th width="50%">Email</th>
                                    <th width="225px">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Basic Tables end -->

    @include('administrator.pembina.modal.detail')
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
                    [0, 'asc']
                ],
                scrollX: true, // Enable horizontal scrolling
                ajax: {
                    url: '{{ route('admin.pembina.getDataArsip') }}',
                    dataType: "JSON",
                    type: "GET",
                    data: function(d) {
                        d.status = getStatus();
                        d.usergroup = getUserGroup();
                    }

                },
                columns: [{
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                    },
                    {
                        data: 'user_group.name',
                        name: 'user_group.name'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        searchable: false,
                        class: 'text-center'
                    }
                ],
            });

            var optionToast = {
                classname: "toast",
                transition: "fade",
                insertBefore: true,
                duration: 4000,
                enableSounds: true,
                autoClose: true,
                progressBar: true,
                sounds: {
                    info: toastMessages.path + "/sounds/info/1.mp3",
                    // path to sound for successfull message:
                    success: toastMessages.path + "/sounds/success/1.mp3",
                    // path to sound for warn message:
                    warning: toastMessages.path + "/sounds/warning/1.mp3",
                    // path to sound for error message:
                    error: toastMessages.path + "/sounds/error/1.mp3",
                },

                onShow: function(type) {
                    console.log("a toast " + type + " message is shown!");
                },
                onHide: function(type) {
                    console.log("the toast " + type + " message is hidden!");
                },

                // the placement where prepend the toast container:
                prependTo: document.body.childNodes[0],
            };


            $(document).on('click', '.delete', function(event) {
                var id = $(this).data('id');
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success mx-4',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                });

                swalWithBootstrapButtons.fire({
                    title: 'Apakah anda yakin ingin menghapus data ini secara permanent',
                    icon: 'warning',
                    buttonsStyling: false,
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Saya yakin!',
                    cancelButtonText: 'Tidak, Batalkan!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: "{{ route('admin.pembina.forceDelete') }}",
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "_method": "DELETE",
                                "id": id,
                            },
                            success: function() {
                                // data_table.ajax.url(
                                //         '{{ route('admin.pembina.getData') }}')
                                //     .load();
                                data_table.ajax.reload(null, false);

                                var toasty = new Toasty(optionToast);
                                toasty.configure(optionToast);
                                toasty.success('Data berhasil dihapus secara permanent');

                                // Remove the deleted row from the DataTable without reloading the page
                                // data_table.row($(this).parents('tr')).remove().draw();
                            }
                        });
                    }
                });
            });

            $(document).on('click', '.restore', function(event) {
                var id = $(this).data('id');
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success mx-4',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                });

                swalWithBootstrapButtons.fire({
                    title: 'Apakah anda yakin ingin memulihkan data ini',
                    icon: 'warning',
                    buttonsStyling: false,
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Saya yakin!',
                    cancelButtonText: 'Tidak, Batalkan!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "PUT",
                            url: "{{ route('admin.pembina.restore') }}",
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "_method": "PUT",
                                "id": id,
                            },
                            success: function() {
                                // data_table.ajax.url(
                                //         '{{ route('admin.pembina.getData') }}')
                                //     .load();
                                data_table.ajax.reload(null, false);

                                var toasty = new Toasty(optionToast);
                                toasty.configure(optionToast);
                                toasty.success('Data berhasil dipulihkan');

                                // Remove the PUT row from the DataTable without reloading the page
                                // data_table.row($(this).parents('tr')).remove().draw();
                            }
                        });
                    }
                });
            });

            $('#filterButton').on('click', function() {
                $('#filter_section').slideToggle();

            });

            var optionUserGroup = $('#filterusergroup');


            optionUserGroup.html(
                '<option id="loadingSpinner" style="display: none;">' +
                '<i class="fas fa-spinner fa-spin">' +
                '</i> Sedang memuat...</option>'
            );

            var loadingSpinner = $('#loadingSpinner');

            loadingSpinner.show(); // Tampilkan elemen animasi

            $.ajax({
                url: '{{ route('admin.pembina.getUserGroup') }}',
                method: 'GET',
                success: function(response) {
                    var data = response.usergroup;
                    var optionsHtml = ''; // Store the generated option elements

                    // Iterate through each user group in the response data
                    for (var i = 0; i < data.length; i++) {
                        var userGroup = data[i];
                        optionsHtml += '<option value="' + userGroup.id + '">' + userGroup
                            .name + '</option>';
                    }

                    // Construct the final dropdown HTML
                    var finalDropdownHtml = '<option value="">Semua</option>' + optionsHtml;

                    optionUserGroup.html(finalDropdownHtml);

                    loadingSpinner.hide(); // Hide the loading spinner after data is loaded
                },
                error: function() {
                    // Handle the error case if the AJAX request fails
                    console.error('Gagal memuat data User Group.');
                    optionUserGroup.html('<option>Gagal memuat data</option>')
                    loadingSpinner
                        .hide(); // Hide the loading spinner even if there's an error
                }
            });

            $('#filter_submit').on('click', function(event) {
                event.preventDefault(); // Prevent the default form submission behavior

                // Get the filter value using the getStatus() function
                var filterStatus = getStatus();
                var filterUserGroup = getUserGroup();

                // Update the DataTable with the filtered data
                data_table.ajax.url('{{ route('admin.pembina.getData') }}?status=' + filterStatus +
                        '|usergroup=' + filterUserGroup)
                    .load();
            });

            function getStatus() {
                return $("#filterstatus").val();
            }

            function getUserGroup() {
                return $("#filterusergroup").val();
            }
        });
    </script>
@endpush
