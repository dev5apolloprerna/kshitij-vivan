

@extends('layouts.front')
@section('title', 'Home')
@section('content')
    <style>
        .modal-header {
            background: linear-gradient(to right, #78c046, #26a9cd);
            color: white;
        }

        .btn:hover {
            color: black;
        }

        .btn1:hover {
            color: black;
        }

        .btn-modal {
            color: #fff;
            background: linear-gradient(to right, #78c046, #26a9cd);
            font-weight: bold;
            border-radius: 8px;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px !important;
            font-family: "Archivo", sans-serif;
            font-size: 15px !important;
            margin: 0px 5px 5px 0px;
            border: 1px solid #ccc;

        }

        .btn-modal:hover {
            background: #fff !important;
            color: black;
            transition: all 1s ease-in-out;
        }

        .cnt {
            background-color: white;
            box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
            border-radius: 10px;
            padding: 20px;
            / left: 20%;/
        }

        .modal-header {
            background: linear-gradient(to right, #78c046, #26a9cd);
            color: white;
            font-size: 17px;
            padding: 7px 10px;
        }

        .modal-title {
            margin-bottom: 0;
            line-height: var(--bs-modal-title-line-height);
            font-size: 17px;
        }

        .close {
            border: none;
            color: white;
            background-color: transparent;
            font-size: 22px;
        }


        .email-fr {
            border: 1px solid lightgrey !important;
            margin: 20px 0px 30px 0px !important;
            padding: 15px !important;
        }

        .modal-dialog {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: auto;
            pointer-events: none;
        }
    </style>
    <main>
        <section class="login-area section-gap">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5">
                        <div class="wrapper-login">
                            <form method="POST" action="{{ url('/reset-password') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <input type="hidden" name="email" value="{{ $email }}">
                                <h2 class="text-center">Reset Password</h2>
                                <div class="input-box ">
                                <label for="password" class="form-label">New Password</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                 name="password" required placeholder="Enter your new password">
                                @error('password')
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $message }}</strong>
                                 </span>
                                @enderror                
                                </div>
                                <div class="input-box">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required
                                                    placeholder="Confirm your new password">
                                    <i class='bx bxs-lock-alt'></i>
                                </div><br>
                                <div class="mt-4">
                                 <input type="submit" class="btn btn-success w-100">                
                             </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
 

@endsection 
