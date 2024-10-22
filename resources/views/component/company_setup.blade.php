@extends('index')
@section('content')
    <div class="container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                <div class="page-header">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="title">
                                <h4>Basic Tables</h4>
                            </div>
                            <nav aria-label="breadcrumb" role="navigation">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Basic Tables</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="col-md-6 col-sm-12 text-right">
                            <div class="dropdown">
                                <a class="btn btn-primary dropdown-toggle" href="#" role="button"
                                    data-toggle="dropdown">
                                    January 2018
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="#">Export List</a>
                                    <a class="dropdown-item" href="#">Policies</a>
                                    <a class="dropdown-item" href="#">View Assets</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
             <!-- Responsive tables Start -->
                <div class="pd-20 card-box mb-30">
                    <div class="clearfix mb-20">
                            <h4 class="text-blue text-center">company</h4>
                    </div>
                    <span class="btn btn-primary mb-4 align-middle">Add a company</span>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr class="text-center">
                                    <th scope="col">S.N</th>
                                    <th scope="col">Batch Name</th>
                                    <th scope="col">Starting Date</th>
                                    <th scope="col">Ending Date</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody >
                                <tr class="text-center">
                                    <th scope="row">1</th>
                                    <td>2080</td>
                                    <td>2080-04-01</td>
                                    <td>2080-05-32</td>
                                    <td><span class="badge badge-success">Running</span></td>
                                    <td>
                                        <a href="" class="btn btn-primary">Edit</a>
                                        <a href="" class="btn btn-danger">Delete</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Responsive tables End -->

                  <!-- Responsive tables Start -->
                  <div class="pd-20 card-box mb-30">
                    <div class="clearfix mb-20">
                            <h4 class="text-blue text-center">Course/Program</h4>
                    </div>
                    <span class="btn btn-primary mb-4 align-middle">Add Course</span>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr class="text-center">
                                    <th scope="col">S.N</th>
                                    <th scope="col">Course Name</th>
                                    <th scope="col">Faculty</th>
                                    <th scope="col">University</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody >
                                <tr class="text-center">
                                    <th scope="row">1</th>
                                    <td>Bachelor of Computer Application</td>
                                    <td>Humanity</td>
                                    <td>Tribhuvan University</td>
                                    <td><span class="badge badge-success">Semester</span></td>
                                    <td>
                                        <a href="" class="btn btn-primary">Edit</a>
                                        <a href="" class="btn btn-danger">Delete</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Responsive tables End -->

                  <!-- Responsive tables Start -->
                  <div class="pd-20 card-box mb-30">
                    <div class="clearfix mb-20">
                            <h4 class="text-blue h4 text-center">Type</h4>
                    </div>
                    <span class="btn btn-primary mb-4 align-middle">Add Semester</span>
                    <span class="btn btn-primary mb-4 align-middle">View Semester</span>
                    <span class="btn btn-primary mb-4 align-middle">View Year</span>
                    {{-- Semester year view start --}}

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr class="text-center">
                                    <th scope="col">S.N</th>
                                    <th scope="col">Semester</th>
                                    <th scope="col">Code</th>
                                    <th scope="col">Tag</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody >
                                <tr class="text-center">
                                    <th scope="row">1</th>
                                    <td>First Semester</td>
                                    <td>00245</td>
                                    <td><span class="badge badge-success">Active</span></td>
                                    <td>
                                        <a href="" class="btn btn-primary">Edit</a>
                                        <a href="" class="btn btn-danger">Delete</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                     {{-- Semester year view start --}}
                       {{-- Semester year view start --}}

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr class="text-center">
                                    <th scope="col">S.N</th>
                                    <th scope="col">Year</th>
                                    <th scope="col">Code</th>
                                    <th scope="col">Tag</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody >
                                <tr class="text-center">
                                    <th scope="row">1</th>
                                    <td>First Year</td>
                                    <td>00245</td>
                                    <td><span class="badge badge-success">Active</span></td>
                                    <td>
                                        <a href="" class="btn btn-primary">Edit</a>
                                        <a href="" class="btn btn-danger">Delete</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                     {{-- Semester year view start --}}
                </div>
                <!-- Responsive tables End -->


            </div>
        </div>
    </div>
@endsection
