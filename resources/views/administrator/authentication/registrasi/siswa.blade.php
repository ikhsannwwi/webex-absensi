@extends('administrator.authentication.main')

@section('content')
    <div class="card">
        <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center">
                <a href="index.html" class="app-brand-link gap-2">
                    <span class="app-brand-logo demo">
                        <svg width="25" viewBox="0 0 25 42" version="1.1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink">
                            <defs>
                                <path
                                    d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z"
                                    id="path-1"></path>
                                <path
                                    d="M5.47320593,6.00457225 C4.05321814,8.216144 4.36334763,10.0722806 6.40359441,11.5729822 C8.61520715,12.571656 10.0999176,13.2171421 10.8577257,13.5094407 L15.5088241,14.433041 L18.6192054,7.984237 C15.5364148,3.11535317 13.9273018,0.573395879 13.7918663,0.358365126 C13.5790555,0.511491653 10.8061687,2.3935607 5.47320593,6.00457225 Z"
                                    id="path-3"></path>
                                <path
                                    d="M7.50063644,21.2294429 L12.3234468,23.3159332 C14.1688022,24.7579751 14.397098,26.4880487 13.008334,28.506154 C11.6195701,30.5242593 10.3099883,31.790241 9.07958868,32.3040991 C5.78142938,33.4346997 4.13234973,34 4.13234973,34 C4.13234973,34 2.75489982,33.0538207 2.37032616e-14,31.1614621 C-0.55822714,27.8186216 -0.55822714,26.0572515 -4.05231404e-15,25.8773518 C0.83734071,25.6075023 2.77988457,22.8248993 3.3049379,22.52991 C3.65497346,22.3332504 5.05353963,21.8997614 7.50063644,21.2294429 Z"
                                    id="path-4"></path>
                                <path
                                    d="M20.6,7.13333333 L25.6,13.8 C26.2627417,14.6836556 26.0836556,15.9372583 25.2,16.6 C24.8538077,16.8596443 24.4327404,17 24,17 L14,17 C12.8954305,17 12,16.1045695 12,15 C12,14.5672596 12.1403557,14.1461923 12.4,13.8 L17.4,7.13333333 C18.0627417,6.24967773 19.3163444,6.07059163 20.2,6.73333333 C20.3516113,6.84704183 20.4862915,6.981722 20.6,7.13333333 Z"
                                    id="path-5"></path>
                            </defs>
                            <g id="g-app-brand" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g id="Brand-Logo" transform="translate(-27.000000, -15.000000)">
                                    <g id="Icon" transform="translate(27.000000, 15.000000)">
                                        <g id="Mask" transform="translate(0.000000, 8.000000)">
                                            <mask id="mask-2" fill="white">
                                                <use xlink:href="#path-1"></use>
                                            </mask>
                                            <use fill="#696cff" xlink:href="#path-1"></use>
                                            <g id="Path-3" mask="url(#mask-2)">
                                                <use fill="#696cff" xlink:href="#path-3"></use>
                                                <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-3"></use>
                                            </g>
                                            <g id="Path-4" mask="url(#mask-2)">
                                                <use fill="#696cff" xlink:href="#path-4"></use>
                                                <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-4"></use>
                                            </g>
                                        </g>
                                        <g id="Triangle"
                                            transform="translate(19.000000, 11.000000) rotate(-300.000000) translate(-19.000000, -11.000000) ">
                                            <use fill="#696cff" xlink:href="#path-5"></use>
                                            <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-5"></use>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </span>
                    <span class="app-brand-text demo text-body fw-bolder">Sneat</span>
                </a>
            </div>
            <!-- /Logo -->
            <h4 class="mb-2">Welcome to Sneat! 👋</h4>
            <p class="mb-4">Please sign-in to your account and start the adventure</p>

            <form action="{{ route('admin.registrasi.siswa.save') }}" method="POST" id="form" novalidate=""
                data-parsley-validate>
                @csrf
                @method('POST')
                <div class="mb-3">
                    <label for="inputNama" class="form-label">Nama</label>
                    <select class="form-control" name="eskul" id="inputEskul" data-parsley-required="true">

                    </select>
                </div>
                <div class="mb-3">
                    <label for="inputNama" class="form-label">Nama</label>
                    <input type="text" class="form-control" name="name" id="inputNama" placeholder="Masukan Nama"
                        autofocus data-parsley-required="true" autocomplete="off" />
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" class="form-control" name="email" id="email" placeholder="Enter your email"
                        data-parsley-type="email" data-parsley-trigger="change"
                        data-parsley-error-message="Masukan alamat email yang valid." autofocus data-parsley-required="true"
                        autocomplete="off" />
                    <div class="" style="color: #dc3545" id="accessErrorEmail"></div>
                </div>
                <div class="mb-3">
                    <label for="inputNis" class="form-label">Nis</label>
                    <input type="text" class="form-control" name="nis" id="inputNis" placeholder="Enter your nis"
                        autofocus data-parsley-required="true" autocomplete="off" />
                    <div class="" style="color: #dc3545" id="accessErrorNis"></div>
                </div>
                <div class="mb-3">
                    <label for="inputTelepon" class="form-label">No Telepon</label>
                    <input type="text" class="form-control" name="telepon" id="inputTelepon"
                        placeholder="Enter your telepon" autofocus data-parsley-required="true" autocomplete="off" />
                    <div class="" style="color: #dc3545" id="accessErrorTelepon"></div>
                </div>
                <div class="mb-3 form-password-toggle">
                    <div class="d-flex justify-content-between">
                        <label class="form-label" for="password">Password</label>
                    </div>
                    <div class="input-group input-group-merge">
                        <input type="password" class="form-control" name="password" id="passwordField"
                            autocomplete="off"
                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                            aria-describedby="password" />
                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                        <div class="" style="color: #dc3545" id="accessErrorPassword"></div>
                    </div>
                </div>
                <div class="mb-3 form-password-toggle">
                    <div class="d-flex justify-content-between">
                        <label class="form-label" for="password">Konfirmasi Password</label>
                    </div>
                    <div class="input-group input-group-merge">
                        <input type="password" class="form-control" name="konfirmasi_password"
                            id="konfirmasiPasswordField" autocomplete="off"
                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                            aria-describedby="password" />
                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                        <div class="" style="color: #dc3545" id="accessErrorKonfirmasiPasssword"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <button type="submit" id="formSubmit" class="btn btn-primary d-grid w-100" tabindex="4">
                        <span class="indicator-label">Submit</span>
                        <span class="indicator-progress" style="display: none;">
                            Tunggu Sebentar...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset_administrator('assets/plugins/choices-select/choices.css') }}">
@endpush

@push('js')
    <script src="{{ asset_administrator('assets/plugins/choices-select/choices.js') }}"></script>

    <script src="{{ asset_administrator('assets/plugins/parsleyjs/parsley.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var multipleFetch = new Choices('#inputEskul', {
                allowHTML: false,
                placeholder: true,
                placeholderValue: 'Pick an Strokes record',
                maxItemCount: 5,
            }).setChoices(function() {
                return fetch(
                        'https://webex.smknegeri1garut.sch.id/api/eskul'
                    )
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(response) {
                        let data = response.data
                        return data.map(function(data) {
                            return {
                                value: data.id,
                                label: data.nama
                            };
                        });
                    });
            });

            //validate parsley form
            const form = document.getElementById("form");
            const validator = $(form).parsley();

            const submitButton = document.getElementById("formSubmit");


            submitButton.addEventListener("click", async function(e) {
                e.preventDefault();
                indicatorBlock();

                const passwordField = $('#passwordField').val().trim();

                // Perform remote validation
                const remoteValidationResultEmail = await validateRemoteEmail();
                const email = $("#email");
                const accessErrorEmail = $("#accessErrorEmail");
                if (!remoteValidationResultEmail.valid) {
                    // Remote validation failed, display the error message
                    accessErrorEmail.addClass('invalid-feedback');
                    email.addClass('is-invalid');

                    accessErrorEmail.text(remoteValidationResultEmail
                        .errorMessage); // Set the error message from the response
                    indicatorNone();

                    return;
                } else {
                    accessErrorEmail.removeClass('invalid-feedback');
                    email.removeClass('is-invalid');
                    accessErrorEmail.text('');
                }

                // Perform remote validation
                const remoteValidationResultNis = await validateRemoteNis();
                const inputNis = $("#inputNis");
                const accessErrorNis = $("#accessErrorNis");
                if (!remoteValidationResultNis.valid) {
                    // Remote validation failed, display the error message
                    accessErrorNis.addClass('invalid-feedback');
                    inputNis.addClass('is-invalid');

                    accessErrorNis.text(remoteValidationResultNis
                        .errorMessage); // Set the error message from the response

                    return;
                } else {
                    accessErrorNis.removeClass('invalid-feedback');
                    inputNis.removeClass('is-invalid');
                    accessErrorNis.text('');
                }

                // Perform remote validation
                const remoteValidationResultTelepon = await validateRemoteTelepon();
                const inputTelepon = $("#inputTelepon");
                const accessErrorTelepon = $("#accessErrorTelepon");
                if (!remoteValidationResultTelepon.valid) {
                    // Remote validation failed, display the error message
                    accessErrorTelepon.addClass('invalid-feedback');
                    inputTelepon.addClass('is-invalid');

                    accessErrorTelepon.text(remoteValidationResultTelepon
                        .errorMessage); // Set the error message from the response

                    return;
                } else {
                    accessErrorTelepon.removeClass('invalid-feedback');
                    inputTelepon.removeClass('is-invalid');
                    accessErrorTelepon.text('');
                }

                if (passwordField !== '') {
                    if (!validatePasswordConfirmation()) {
                        return;
                    }
                }

                const inputPassword = $("#passwordField");
                let accessErrorPassword = $("#accessErrorPassword");
                if (passwordField === '') {
                    accessErrorPassword.addClass('invalid-feedback');
                    inputPassword.addClass('is-invalid');

                    accessErrorPassword.text(
                        'Value is required'); // Set the error message from the response
                    indicatorNone();

                    return
                } else {
                    accessErrorPassword.removeClass('invalid-feedback');
                    inputPassword.removeClass('is-invalid');
                    accessErrorPassword.text('');
                }

                // Validate the form using Parsley
                if ($(form).parsley().validate()) {
                    indicatorSubmit();
                    form.submit();
                } else {
                    // Handle validation errors
                    const validationErrors = [];
                    $(form).find(':input').each(function() {
                        const field = $(this);
                        if (!field.parsley().isValid()) {
                            indicatorNone();
                            const attrName = field.attr('name');
                            const errorMessage = field.parsley().getErrorsMessages().join(
                                ', ');
                            validationErrors.push(attrName + ': ' + errorMessage);
                        }
                    });
                    console.log("Validation errors:", validationErrors.join('\n'));
                }
            });

            function indicatorSubmit() {
                submitButton.querySelector('.indicator-label').style.display =
                    'inline-block';
                submitButton.querySelector('.indicator-progress').style.display =
                    'none';
            }

            function indicatorNone() {
                submitButton.querySelector('.indicator-label').style.display =
                    'inline-block';
                submitButton.querySelector('.indicator-progress').style.display =
                    'none';
                submitButton.disabled = false;
            }

            function indicatorBlock() {
                // Disable the submit button and show the "Please wait..." message
                submitButton.disabled = true;
                submitButton.querySelector('.indicator-label').style.display = 'none';
                submitButton.querySelector('.indicator-progress').style.display =
                    'inline-block';
            }

            $('#passwordField, #konfirmasiPasswordField').on('input', function() {
                validatePasswordConfirmation();
            });

            function validatePasswordConfirmation() {
                const passwordField = $('#passwordField');
                const accessErrorPassword = $("#accessErrorPassword");
                const konfirmasiPasswordField = $('#konfirmasiPasswordField');
                const accessErrorKonfirmasiPassword = $("#accessErrorKonfirmasiPasssword");

                if (passwordField.val().length < 8) {
                    passwordField.addClass('is-invalid');
                    accessErrorPassword.text('Password harus memiliki setidaknya 8 karakter');
                    indicatorNone();
                    return false;
                } else if (passwordField.val() !== konfirmasiPasswordField.val()) {
                    passwordField.removeClass('is-invalid');
                    accessErrorPassword.text('');
                    konfirmasiPasswordField.addClass('is-invalid');
                    accessErrorKonfirmasiPassword.text('Konfirmasi Password harus sama dengan Password');
                    indicatorNone();
                    return false;
                } else {
                    passwordField.removeClass('is-invalid');
                    accessErrorPassword.text('');
                    konfirmasiPasswordField.removeClass('is-invalid');
                    accessErrorKonfirmasiPassword.text('');
                    return true;
                }
            }

            async function validateRemoteEmail() {
                const email = $('#email');
                const remoteValidationUrl = "{{ route('admin.registrasi.siswa.checkEmail') }}";
                const csrfToken = "{{ csrf_token() }}";

                try {
                    const response = await $.ajax({
                        method: "POST",
                        url: remoteValidationUrl,
                        data: {
                            _token: csrfToken,
                            email: email.val()
                        }
                    });

                    // Assuming the response is JSON and contains a "valid" key
                    return {
                        valid: response.valid === true,
                        errorMessage: response.message
                    };
                } catch (error) {
                    console.error("Remote validation error:", error);
                    return {
                        valid: false,
                        errorMessage: "An error occurred during validation."
                    };
                }
            }

            async function validateRemoteNis() {
                const inputNis = $('#inputNis');
                const remoteValidationUrl = "{{ route('admin.registrasi.siswa.checkNis') }}";
                const csrfToken = "{{ csrf_token() }}";

                try {
                    const response = await $.ajax({
                        method: "POST",
                        url: remoteValidationUrl,
                        data: {
                            _token: csrfToken,
                            nis: inputNis.val()
                        }
                    });

                    // Assuming the response is JSON and contains a "valid" key
                    return {
                        valid: response.valid === true,
                        errorMessage: response.message
                    };
                } catch (error) {
                    console.error("Remote validation error:", error);
                    return {
                        valid: false,
                        errorMessage: "An error occurred during validation."
                    };
                }
            }

            async function validateRemoteTelepon() {
                const inputTelepon = $('#inputTelepon');
                const remoteValidationUrl = "{{ route('admin.registrasi.siswa.checkTelepon') }}";
                const csrfToken = "{{ csrf_token() }}";

                try {
                    const response = await $.ajax({
                        method: "POST",
                        url: remoteValidationUrl,
                        data: {
                            _token: csrfToken,
                            telepon: inputTelepon.val()
                        }
                    });

                    // Assuming the response is JSON and contains a "valid" key
                    return {
                        valid: response.valid === true,
                        errorMessage: response.message
                    };
                } catch (error) {
                    console.error("Remote validation error:", error);
                    return {
                        valid: false,
                        errorMessage: "An error occurred during validation."
                    };
                }
            }
        });
    </script>
@endpush
