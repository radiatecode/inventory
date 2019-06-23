<!DOCTYPE html>
<html lang="en">
<head>
   @include('front.partials._head')
</head>

<body class="login">
<div>
    <div class="login_wrapper">
        <div >
            <section class="login_content">
                @include('front.partials._message')
                <form action="{{ route('user.register') }}" method="post">
                    @csrf
                    {{--{{ csrf_field() }}--}}
                    <h1>Create Account</h1>
                    <div>
                        <input type="text" class="form-control" placeholder="Name" name="name" />
                    </div>
                    <div>
                        <input type="email" class="form-control" placeholder="Email" name="email"  />
                    </div>
                    <div>
                        <input type="password" class="form-control" placeholder="Password" name="password" />
                    </div>
                    <div>
                        <input type="text" class="form-control" placeholder="Title" name="title"  />
                    </div>
                    <div>
                        <input type="text" class="form-control" placeholder="Location" name="location"  />
                    </div>
                    <div>
                        <button type="submit" class="btn btn-default submit">Submit</button>
                    </div>

                    <div class="clearfix"></div>

                    <div class="separator">
                        <p class="change_link">Already a member ?
                            <a href="{{ url('user/login') }}" class="to_register"> Log in </a>
                        </p>

                        <div class="clearfix"></div>
                        <br />

                        <div>

                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
</body>
</html>
