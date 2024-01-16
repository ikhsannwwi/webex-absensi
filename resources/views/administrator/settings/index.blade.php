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
                        <li class="breadcrumb-item"><a href="{{ route('admin.settings') }}">Setting</a></li>
                        <li class="breadcrumb-item active" aria-current="page">General</li>
                    </ol>
                </nav>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form action="{{ route('admin.settings.general.update') }}" method="post" enctype="multipart/form-data"
                        class="form" id="form" data-parsley-validate>
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group mandatory">
                                    <label for="namaAppField" class="form-label">Nama App</label>
                                    <input type="text" id="namaAppField" class="form-control"
                                        placeholder="Masukan Nama App"
                                        value="{{ array_key_exists('nama_app_admin', $settings) ? $settings['nama_app_admin'] : '' }}"
                                        name="nama_app_admin" autocomplete="off" data-parsley-required="true">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group mandatory">
                                    <label for="footerAppAdmin" class="form-label">Footer App Admin</label>
                                    <input type="text" id="footerAppAdmin" class="form-control"
                                        placeholder="Masukan Footer App Admin"
                                        value="{{ array_key_exists('footer_app_admin', $settings) ? $settings['footer_app_admin'] : '' }}"
                                        name="footer_app_admin" autocomplete="off" data-parsley-required="true">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group mandatory">
                                    <label for="logoAppAdminInputFile" class="form-label">Logo App Admin</label>
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="preview_logoappadmin thumbnail mb20">
                                            <img width="500"
                                                src="{{ array_key_exists('logo_app_admin', $settings) ? img_src($settings['logo_app_admin'], 'settings') : '' }}">
                                        </div>
                                        <div class="mt-3">
                                            <label for="logoAppAdminInputFile" class="btn btn-light btn-file">
                                                <span class="fileinput-new">Select image</span>
                                                <input type="file" class="d-none" id="logoAppAdminInputFile"
                                                    name="logo_app_admin">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group mandatory">
                                    <label for="faviconInputFile" class="form-label">Favicon</label>
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="preview_favicon thumbnail mb20">
                                            <img width="500"
                                                src="{{ array_key_exists('favicon', $settings) ? img_src($settings['favicon'], 'settings') : '' }}">
                                        </div>
                                        <div class="mt-3">
                                            <label for="faviconInputFile" class="btn btn-light btn-file">
                                                <span class="fileinput-new">Select image</span>
                                                <input type="file" class="d-none" id="faviconInputFile" name="favicon">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group mandatory">
                                    <label for="backgroundLoginPanelAdminInputFile" class="form-label">Background Login
                                        Panel Admin</label>
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="preview_background_login_panel_admin thumbnail mb20" data-trigger="fileinput">
                                            <img width="500"
                                                src="{{ array_key_exists('background_login_panel_admin', $settings) ? img_src($settings['background_login_panel_admin'], 'settings') : '' }}">
                                        </div>
                                        <div class="mt-3">
                                            <label for="backgroundLoginPanelAdminInputFile" class="btn btn-light btn-file">
                                                <span class="fileinput-new">Select image</span>
                                                <input type="file" class="d-none" id="backgroundLoginPanelAdminInputFile"
                                                    name="background_login_panel_admin">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" id="formSubmit" class="btn btn-primary me-1 mb-1">
                                    <span class="indicator-label">Submit</span>
                                    <span class="indicator-progress" style="display: none;">
                                        Tunggu Sebentar...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                                <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
    <!-- Basic Tables end -->
@endsection

@push('js')
    <script src="{{ asset('templateAdmin/assets/extensions/parsleyjs/parsley.min.js') }}"></script>
    <script src="{{ asset('templateAdmin/assets/js/pages/parsley.js') }}"></script>

    <!-- Tambahkan FileInput JavaScript -->
    <script src="{{ asset_administrator('assets/plugins/form-jasnyupload/fileinput.min.js') }}"></script>

    <script>
        const logoAppAdminInputFile = document.getElementById("logoAppAdminInputFile");
        const previewContainerLogoAppAdmin = document.querySelector(".preview_logoappadmin");
    
        logoAppAdminInputFile.addEventListener("change", function() {
            const files = this.files;
    
            // Hapus gambar-gambar sebelumnya
            previewContainerLogoAppAdmin.innerHTML = '';
    
            // Ambil satu file saja
            const file = files[0];
            const imageType = /^image\//;
    
            if (imageType.test(file.type)) {
                const imgContainer = document.createElement("div");
                imgContainer.classList.add("img-thumbnail-container");
    
                const img = document.createElement("img");
                img.classList.add("img-thumbnail");
                img.width = 500; // Sesuaikan ukuran gambar sesuai kebutuhan
                img.src = URL.createObjectURL(file);
    
                imgContainer.appendChild(img);
                previewContainerLogoAppAdmin.appendChild(imgContainer);
            }
        });
    
        const faviconInputFile = document.getElementById("faviconInputFile");
        const previewContainerFavicon = document.querySelector(".preview_favicon");
    
        faviconInputFile.addEventListener("change", function() {
            const files = this.files;
    
            // Hapus gambar-gambar sebelumnya
            previewContainerFavicon.innerHTML = '';
    
            // Ambil satu file saja
            const file = files[0];
            const imageType = /^image\//;
    
            if (imageType.test(file.type)) {
                const imgContainer = document.createElement("div");
                imgContainer.classList.add("img-thumbnail-container");
    
                const img = document.createElement("img");
                img.classList.add("img-thumbnail");
                img.width = 500; // Sesuaikan ukuran gambar sesuai kebutuhan
                img.src = URL.createObjectURL(file);
    
                imgContainer.appendChild(img);
                previewContainerFavicon.appendChild(imgContainer);
            }
        });
    
        const backgroundLoginPanelAdminInputFile = document.getElementById("backgroundLoginPanelAdminInputFile");
        const previewContainerBackgroundLoginPanelAdmin = document.querySelector(".preview_background_login_panel_admin");
    
        backgroundLoginPanelAdminInputFile.addEventListener("change", function() {
            const files = this.files;
    
            // Hapus gambar-gambar sebelumnya
            previewContainerBackgroundLoginPanelAdmin.innerHTML = '';
    
            // Ambil satu file saja
            const file = files[0];
            const imageType = /^image\//;
    
            if (imageType.test(file.type)) {
                const imgContainer = document.createElement("div");
                imgContainer.classList.add("img-thumbnail-container");
    
                const img = document.createElement("img");
                img.classList.add("img-thumbnail");
                img.width = 500; // Sesuaikan ukuran gambar sesuai kebutuhan
                img.src = URL.createObjectURL(file);
    
                imgContainer.appendChild(img);
                previewContainerBackgroundLoginPanelAdmin.appendChild(imgContainer);
            }
        });
    </script>
    
    
    <script type="text/javascript">
        $(document).ready(function() {



            $("#logoAppAdminInputFile").fileinput({
                showUpload: false, // Hilangkan tombol "Upload"
                showRemove: false, // Hilangkan tombol "Remove"
                language: 'id', // Gantilah LANG dengan bahasa yang sesuai
                // Tambahan opsi sesuai kebutuhan Anda
            });
            $("#faviconInputFile").fileinput({
                showUpload: false, // Hilangkan tombol "Upload"
                showRemove: false, // Hilangkan tombol "Remove"
                language: 'id', // Gantilah LANG dengan bahasa yang sesuai
                // Tambahan opsi sesuai kebutuhan Anda
            });
            $("#backgroundLoginPanelAdminInputFile").fileinput({
                showUpload: false, // Hilangkan tombol "Upload"
                showRemove: false, // Hilangkan tombol "Remove"
                language: 'id', // Gantilah LANG dengan bahasa yang sesuai
                // Tambahan opsi sesuai kebutuhan Anda
            });

            //validate parsley form
            const form = document.getElementById("form");
            const validator = $(form).parsley();

            const submitButton = document.getElementById("formSubmit");

            form.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                }
            });

            submitButton.addEventListener("click", async function(e) {
                e.preventDefault();

                // Validate the form using Parsley
                if ($(form).parsley().validate()) {
                    // Disable the submit button and show the "Please wait..." message
                    submitButton.querySelector('.indicator-label').style.display = 'none';
                    submitButton.querySelector('.indicator-progress').style.display =
                        'inline-block';

                    // Perform your asynchronous form submission here
                    // Simulating a 2-second delay for demonstration
                    setTimeout(function() {
                        // Re-enable the submit button and hide the "Please wait..." message
                        submitButton.querySelector('.indicator-label').style.display =
                            'inline-block';
                        submitButton.querySelector('.indicator-progress').style.display =
                            'none';

                        // Submit the form
                        form.submit();
                    }, 2000);
                } else {
                    // Handle validation errors
                    const validationErrors = [];
                    $(form).find(':input').each(function() {
                        const field = $(this);
                        if (!field.parsley().isValid()) {
                            const attrName = field.attr('name');
                            const errorMessage = field.parsley().getErrorsMessages().join(
                                ', ');
                            validationErrors.push(attrName + ': ' + errorMessage);
                        }
                    });
                    console.log("Validation errors:", validationErrors.join('\n'));
                }
            });

        });
    </script>
@endpush
