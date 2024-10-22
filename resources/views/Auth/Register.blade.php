<!doctype html>
<html lang="en">

<head>
    <title>Register Pannel</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body class="bg-dark">

    <div class="container mt-4 p-2 col-md-4">
        <img class="card-img-top" src="{{ asset('src/images/registerit.webp') }}" width="100" alt="">
        <div class="card border-primary">
            <div class="card-body rounded">
                <form action="{{ route('storeRegister') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="">Name</label>
                        <input type="text" name="name" id="" value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror" placeholder="">
                        @error('name')
                            <small id="helpId" class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" name="email" id="" value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror" placeholder="">
                        @error('name')
                            <small id="helpId" class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="user_type"> Role</label>
                        <select class="form-control" name="role" id="role">
                            <option value = "user">user</option>
                            <option value = "admin">admin</option>
                            <option value = "other">other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Password</label>
                        <input type="password" name="password" id=""
                            class="form-control @error('password') is-invalid @enderror" placeholder="">
                        @error('password')
                            <small id="helpId" class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Confirm Password</label>
                        <input type="password" name="password_confirmation" id=""
                            class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="">
                        @error('password_confirmation')
                            <small id="helpId" class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <button class="btn btn-primary btn-lg">Register</button>
                </form>
            </div>
        </div>
    </div>
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
</body>

</html>
