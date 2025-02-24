<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard')</title>
    <link rel="stylesheet" href="{{ asset('assets-otika/css/app.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('assets-otika/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets-otika/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('assets-otika/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets-otika/css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('assets-otika/css/custom.css') }}">
    <link rel='shortcut icon' type='image/x-icon' href='assets/img/favicon.ico' />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://ujian-sekolah.skripsian.site/assets/extensions/simple-datatables/style.css">
    <link rel="stylesheet" href="https://ujian-sekolah.skripsian.site/assets/compiled/css/table-datatable.css">

    <link rel="stylesheet"
        href="https://ujian-sekolah.skripsian.site/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://ujian-sekolah.skripsian.site/assets/compiled/css/table-datatable-jquery.css">

    @stack('styles')

    <style>
        * {
            /* border: none!important; */
        }
    </style>
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            @include('layouts-otika.navbar')

            @include('layouts-otika.sidebar')

            <div class="main-content">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @yield('content')
                <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form id="editProfileForm" action="{{ route('profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nama</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ auth()->user()->name }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ auth()->user()->email }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password Baru (Opsional)</label>
                                        <input type="password" class="form-control" id="password" name="password">
                                        <small class="text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            @include('layouts-otika.footer')
        </div>
    </div>
    <script src="{{ asset('assets-otika/js/app.min.js') }}"></script>
    <script src="{{ asset('assets-otika/bundles/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets-otika/js/page/index.js') }}"></script>
    <script src="{{ asset('assets-otika/js/scripts.js') }}"></script>
    <script src="{{ asset('assets-otika/js/custom.js') }}"></script>
    {{-- <script src="{{ asset('assets-otika/bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets-otika/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"> --}}
    </script>
    {{-- <script src="{{ asset('assets-otika/bundles/jquery-ui/jquery-ui.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('assets-otika/js/page/datatables.js') }}"></script> --}}
    <!-- Bootstrap Bundle (JS + Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- <script src="https://ujian-sekolah.skripsian.site/assets/extensions/jquery/jquery.min.js"></script> --}}
    <script src="https://ujian-sekolah.skripsian.site/assets/extensions/datatables.net/js/jquery.dataTables.min.js">
    </script>
    <script src="https://ujian-sekolah.skripsian.site/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js">
    </script>
    <script src="https://ujian-sekolah.skripsian.site/assets/static/js/pages/datatables.js"></script>

    @stack('scripts')

    <script>
        function confirmDelete(id) {
            if (confirm('Apakah kamu yakin mau hapus data ini?')) {
                document.getElementById('deleteForm' + id).submit();
            }
        }
    </script>
    <script>
        $(document).ready(function() {
            $('.table').DataTable({
                "order": [
                    [0, "asc"]
                ],
                "lengthMenu": [5, 10, 50, 100]
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editProfileForm = document.getElementById('editProfileForm');

            if (editProfileForm) {
                editProfileForm.addEventListener('submit', function(event) {
                    event.preventDefault();

                    fetch(this.action, {
                            method: 'POST',
                            body: new FormData(this),
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(err => {
                                    throw err;
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                alert('Profile updated successfully!');
                                location.reload();
                            } else {
                                alert('Profile update successfully.');
                                location.reload();
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            if (error.errors) {
                                // Tampilkan pesan error validasi
                                let errorMessages = Object.values(error.errors).join('\n');
                                alert('Validation errors:\n' + errorMessages);
                            } else {
                                alert('Profile updated successfully!');
                                location.reload();
                            }
                        });
                });
            }
        });
    </script>
</body>

</html>
