@extends('guest')

@section('content')

	<div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
		<div class="container mx-auto d-flex">
			<div class="row align-items-center">
				<div class="col-md-6 col-lg-7">
					<img src="vendors/images/login-page-img.png" alt="">
				</div>
				<div class="col-md-6 col-lg-5">
					<div class="login-box bg-white box-shadow border-radius-10">
						<div class="login-title">
							<h2 class="text-center text-primary">Login To System</h2>
						</div>
                        @if (session()->has('message'))
                            <div class="alert alert-success text-center alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Close</span>
                                </button>
                                <strong>{{ session()->get('message') }}</strong>
                            </div>
                            {{-- @php
                                $array =$OUrservices::where('client',)
                                for($array){
                                   $totalAmount = i+$array;
                                }
                                <input type="number" name="totalsum"  class="form-control ">

                            @endphp --}}
                        @endif
                        @if (session()->has('error'))
                            <div class="alert alert-danger text-center alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Close</span>
                                </button>
                                <strong>{{ session()->get('error') }}</strong>
                            </div>
                        @endif
						<form action="{{ route('store.login') }}" method="POST">
                            @csrf
							<div class="input-group custom">
								<input type="text" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" placeholder="Email">
								<div class="input-group-append custom">
                                    <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
								</div>
							</div>
                            <div class="">
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
							<div class="input-group custom">
								<input type="password" name="password"  class="form-control form-control-lg @error('password') is-invalid @enderror" placeholder="**********">
								<div class="input-group-append custom">
                                    <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
								</div>
							</div>
                           <div>
                            @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                           </div>
                            <button class="btn btn-primary btn-lg btn-block">Sign In</button>
                        </form>
							<div class="row">
								<div class="col-sm-12">
									<div class="input-group mb-0">
										<!--
											use code for form submit
											<input class="btn btn-primary btn-lg btn-block" type="submit" value="Sign In">
										-->
										{{-- <a class="btn btn-primary btn-lg btn-block" href="index.html">Sign In</a> --}}
									</div>
									<div class="font-16 weight-600 pt-10 pb-10 text-center" data-color="#707373">OR</div>
									<div class="input-group mb-0">
										<a class="btn btn-outline-primary btn-lg btn-block" href="{{url('register')}}">Register To Create Account</a>
									</div>
								</div>
							</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
	<!-- js -->


