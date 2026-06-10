@include('owner.components.header')
  <div class="fix-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-6">
                    <div class="card mb-0 h-auto">
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <a href="#"><img class="logo-auth" src="images/logo-full.png" alt=""></a>
                            </div>
                           @if(session('success'))
                                <div id="successAlert" class="alert alert-success alert-dismissible fade show text-center" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                let alertBox = document.getElementById('successAlert');

                                if (alertBox) {
                                    setTimeout(function () {
                                        alertBox.classList.remove('show');
                                        alertBox.classList.add('fade');

                                        setTimeout(() => {
                                            alertBox.remove();
                                        }, 500);
                                    }, 3000); // 3 seconds
                                }
                            });
                            </script>
                            <h4 class="text-center mb-4">Sign in your account</h4>
                            <form action="{{ route('owner.auth') }}" method="POST">
                                @csrf
                                <div class="form-group mb-4">
                                    <label class="form-label" for="email">Email</label>
                                    <input type="email" class="form-control" placeholder="Enter email" id="email" name="email" value="{{ old('email') }}">
                                </div>
                               <div class="form-group position-relative">
                                    <label>Password</label>

                                    <input type="password" id="password" class="form-control pr-5" placeholder="Enter password" name="password">

                                    <!-- Eye Icon -->
                                    <span toggle="#password"
                                        class="fa fa-eye toggle-password"
                                        style="position:absolute; top:38px; right:10px; cursor:pointer;">
                                    </span>
                                </div>

                                <div class="form-row d-flex flex-wrap justify-content-end mb-2 mt-4">
                                    <div class="form-group ms-2">
                                        <a href="#">Forgot Password?</a>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                                </div>
                            </form>
                            <div class="new-account mt-3">
                                <p>Don't have an account? <a class="text-primary" href="{{ route('owner.register') }}">Sign up</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
    
    @include('owner.components.footer')