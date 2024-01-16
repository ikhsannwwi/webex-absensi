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
                        <li class="breadcrumb-item"><a href="{{ route('admin.settings.smtp') }}">Setting</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Smtp</li>
                    </ol>
                </nav>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form action="{{ route('admin.settings.smtp.update') }}" method="post" enctype="multipart/form-data"
                        class="form" id="form" data-parsley-validate>
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group mandatory">
                                    <label for="inputHost" class="form-label">Host</label>
                                    <input type="text" id="inputHost" class="form-control"
                                        placeholder="Masukan Host"
                                        value="{{ array_key_exists('smtp_host', $settings) ? $settings['smtp_host'] : '' }}"
                                        name="host" autocomplete="off" data-parsley-required="true">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group mandatory">
                                    <label for="inputPort" class="form-label">Port</label>
                                    <input type="text" id="inputPort" class="form-control"
                                        placeholder="Masukan Port"
                                        value="{{ array_key_exists('smtp_port', $settings) ? $settings['smtp_port'] : '' }}"
                                        name="port" autocomplete="off" data-parsley-required="true">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group mandatory">
                                    <label for="inputSecurity" class="form-label">Security</label>
                                    <select class="form-select" name="security" id="inputSecurity" data-parsley-required="true">
                                        <option value="">Pilih Data</option>
                                        <option value="ssl" {{ array_key_exists('smtp_security', $settings) ? ($settings['smtp_security'] == 'ssl' ? 'selected' : '') : '' }}>SSL</option>
                                        <option value="tls" {{ array_key_exists('smtp_security', $settings) ? ($settings['smtp_security'] == 'tls' ? 'selected' : '') : '' }}>TLS</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group mandatory">
                                    <label for="inputUser" class="form-label">User</label>
                                    <input type="text" id="inputUser" class="form-control"
                                        placeholder="Masukan User"
                                        value="{{ array_key_exists('smtp_user', $settings) ? $settings['smtp_user'] : '' }}"
                                        name="user" autocomplete="off" data-parsley-required="true">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group mandatory">
                                    <label for="inputPassword" class="form-label">Password</label>
                                    <input type="text" id="inputPassword" class="form-control"
                                        placeholder="Masukan Password"
                                        value="{{ array_key_exists('smtp_password', $settings) ? $settings['smtp_password'] : '' }}"
                                        name="password" autocomplete="off" data-parsley-required="true">
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

    <script type="text/javascript">
        $(document).ready(function() {
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
