<div class="col-md-6 col-12">
    <div class="form-group mandatory">
        <label for="inputEskul" class="form-label">Ekstrakurikuler</label>
        <div class="row">
            <div class="col-8" style="padding-right: 0;">
                <!-- Menggunakan col-8 agar input lebih lebar dan menghapus padding kanan -->
                <input type="text" class="form-control" id="inputEskulName" readonly>
                <input type="text" class="d-none" name="eskul" id="inputEskulId">
            </div>
            <div class="col-4" style="padding-left: 0;">
                <!-- Menggunakan col-4 agar tombol "Search" lebih kecil dan menghapus padding kiri -->
                <a href="#" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEskul">
                    Search
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalEskul" tabindex="-1" aria-labelledby="modalEskulLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEskulLabel">Ekstrakurikuler</h5>
                <button type="button" id="buttonCloseEskul" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalEskulBody">
                <table class="table" id="datatableEskulModal">
                    <thead>
                        <tr>
                            <th width="15px">No</th>
                            <th width="100%">Nama</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="selectData-Eskul">Pilih Data</button>
            </div>
        </div>
    </div>
</div>
@push('js')
    <script>
        function addSelectedClassByEskulId(userGroupId) {
            var table = $('#datatableEskulModal').DataTable();
            // Check if the 'select' extension is available
            if ($.fn.dataTable.Select) {
                // Check if the 'select' extension is initialized for the table
                if (table.select) {
                    // Deselect all rows first
                    table.rows().deselect();
                }
            }
            table.rows().nodes().to$().removeClass('selected'); // Remove 'selected' class from all rows
            if (userGroupId) {
                table.rows().every(function() {
                    var rowData = this.data();
                    if (rowData.id === parseInt(userGroupId)) {
                        // Check if the 'select' extension is available before using 'select' method
                        if ($.fn.dataTable.Select && table.select) {
                            this.select(); // Select the row
                        }
                        $(this.node()).addClass('selected'); // Add 'selected' class
                        return false; // Break the loop
                    }
                });
            }
        }
        $('#modalEskul').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            // Now, you can initialize a new DataTable on the same table.
            $("#datatableEskulModal").DataTable().destroy();
            $('#datatableEskulModal tbody').remove();
            var datatable_eskul = $('#datatableEskulModal').DataTable({
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
                ajax: {
                    url: '{{ route('admin.siswa.getDataEskul') }}',
                    dataType: "JSON",
                    type: "GET",
                },
                columns: [{
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                ],
                drawCallback: function(settings) {
                    // Add 'selected' class based on the content of the input fields
                    var userGroupId = $("#inputEskulId").val();
                    addSelectedClassByEskulId(userGroupId);
                },
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
            // click di baris tabel user
            $('#datatableEskulModal tbody').on('click', 'tr', function() {
                // Remove the 'selected' class from all rows
                $('#datatableEskulModal tbody tr').removeClass('selected');
                // Add the 'selected' class to the clicked row
                $(this).addClass('selected');
                var data = datatable_eskul.row(this).data();
            });
            // click di tombol Pilih Eskul
            $('#selectData-Eskul').off('click').on('click', function(e) {
                e.preventDefault();
                // Get the selected row data
                var selectedRowData = datatable_eskul.rows('.selected').data()[0];
                // Check if any row is selected
                if (selectedRowData) {
                    // Use the selected row data
                    $("#inputEskulName").val(selectedRowData.nama);
                    $("#inputEskulId").val(selectedRowData.id);
                    // Close the modal
                    $('#buttonCloseEskul').click();
                } else {
                    $('#buttonCloseEskul').click();
                    var toasty = new Toasty(optionToast);
                    toasty.configure(optionToast);
                    toasty.error('Pilih salah satu');
                }
            });
            // end click di tombol Pilih Eskul
        });
    </script>
@endpush
