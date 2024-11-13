@extends('layouts.main')
@section('header_file')

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
@endsection

@section('title', 'Edit User ' . $user->name)

@section('content')

    <div class="container mt-4 p-2 col-md-4">
        <img class="card-img-top" src="{{ asset('src/images/registerit.webp') }}" width="100" alt="">
        <div class="card border-primary">
            <div class="card-body rounded">
                <form action="{{ route('updateUser', $user->id) }}" method="POST">
                    @csrf
                    @method('POST') <!-- To specify the HTTP method for the update -->

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                            class="form-control @error('name') is-invalid @enderror" placeholder="">
                        @error('name')
                            <small id="helpId" class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                            class="form-control @error('email') is-invalid @enderror" placeholder="" readonly>
                        @error('email')
                            <small id="helpId" class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="role">Role</label>
                        <select class="form-control" name="role" id="role">
                            <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin
                            </option>
                            <option value="other" {{ old('role', $user->role) == 'other' ? 'selected' : '' }}>Other
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password"
                                class="form-control @error('password') is-invalid @enderror" value="{{ old('password') }}"
                                placeholder="">
                            <div class="input-group-append">
                                <span class="input-group-text" id="toggle-password" style="cursor: pointer;">
                                    <i class="fas fa-eye" id="password-eye"></i>
                                </span>
                            </div>
                        </div>
                        @error('password')
                            <small id="helpId" class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <div class="input-group">
                            <!-- Set value to old('password') for the confirm password field -->
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                value="{{ old('password') }}" placeholder="">
                            <div class="input-group-append">
                                <span class="input-group-text" id="toggle-confirm-password" style="cursor: pointer;">
                                    <i class="fas fa-eye" id="confirm-password-eye"></i>
                                </span>
                            </div>
                        </div>
                        @error('password_confirmation')
                            <small id="helpId" class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>


                    <button type="submit" class="btn btn-primary btn-lg">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('footer_file')

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <!-- Add JavaScript for Toggle -->
    <script>
        document.getElementById("password-eye").addEventListener("click", function() {
            var passwordField = document.getElementById("password");
            var eyeIcon = document.getElementById("password-eye");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            }
        });

        document.getElementById("confirm-password-eye").addEventListener("click", function() {
            var confirmPasswordField = document.getElementById("password_confirmation");
            var eyeIcon = document.getElementById("confirm-password-eye");

            if (confirmPasswordField.type === "password") {
                confirmPasswordField.type = "text";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            } else {
                confirmPasswordField.type = "password";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            }
        });
    </script>
@endsection
