<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ config('app.name') }}">
    <meta name="author" content="{{ config('app.author') }}">

    {{-- Title --}}
    <title>Login - {{ config('app.name') }}</title>

    {{-- Favicon icon --}}
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    {{-- tabler icons CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />
    {{-- Font --}}
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    {{-- Page CSS --}}
    <link rel="stylesheet" href="{{ asset('css/page-auth.css') }}">
</head>

<body>
    <div class="page-wrapper">
        <div class="position-relative overflow-hidden min-vh-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="card auth-card mx-3 mb-0">
                    <div class="card-body">
                        {{-- logo --}}
                        <a href="{{ route('login') }}" class="text-nowrap text-center d-block py-3 w-100 mb-2">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" width="95px">
                        </a>
                        {{-- nama aplikasi --}}
                        <h5 class="login-title text-center mb-5">{{ config('app.name') }}</h5>

                        {{-- menampilkan pesan --}}
                        <x-alert></x-alert>

                        {{-- form login --}}
                        <form action="{{ route('login.authenticate') }}" method="POST">
                            @csrf
                            <div class="form-floating mb-3">
                                <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" placeholder="Username" value="{{ old('username') }}" autocomplete="off">
                                <label>
                                    <i class="ti ti-user"></i>
                                    <span class="border-start ps-3">Username</span>
                                </label>

                                {{-- pesan error untuk username --}}
                                @error('username')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" autocomplete="off">
                                <label>
                                    <i class="ti ti-lock"></i>
                                    <span class="border-start ps-3">Password</span>
                                </label>

                                {{-- pesan error untuk password --}}
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- button login --}}
                            <button type="submit" class="btn btn-primary w-100 mt-3 mb-5">LOGIN</button>

                            {{-- copyright --}}
                            <div class="text-center mb-2">
                                &copy; <span id="year"></span> - 
                                <a href="https://shopee.co.id/codenull" target="_blank" class="text-brand text-decoration-none fw-semibold">
                                    Code Null
                                </a>.
                            </div>
                            
                            <script>
                                // Set tahun otomatis
                                document.getElementById("year").textContent = new Date().getFullYear();
                            
                                // Cegah modifikasi di browser (opsional)
                                Object.freeze(document.getElementById("year"));
                                
                                // Mencegah inspeksi elemen (opsional)
                                document.addEventListener("contextmenu", function (e) {
                                    e.preventDefault();
                                });
                            
                                document.addEventListener("keydown", function (e) {
                                    if (e.ctrlKey && (e.key === "u" || e.key === "s" || e.key === "i")) {
                                        e.preventDefault();
                                    }
                                });
                            </script>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"> </script>
</body>

</html>
