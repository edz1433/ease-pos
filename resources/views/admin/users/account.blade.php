@extends('layouts.master')

@section('body')
<style>
    .bg-form {
        background-color: #e9ecef;
    }
    .form-control:disabled, .form-control[readonly] {
        background-color: #ffffff;
        opacity: 1;
    }
    .form-control-sm {
        height: calc(1.5125rem + 2px);
        padding: .15rem .5rem;
        font-size: .750rem;
        line-height: 1.5;
        border-radius: .2rem;
        background-color: #ffffff !important;
    }
    .btn-sm {
        font-size: 10px !important;
        height: 25px !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }
    .bb {
        border-bottom: 1px solid rgb(145, 138, 138);
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title text-gray"><b>MY ACCOUNT</b></h2>
                </div>
                <div class="card-body">
                    <div class="panel panel-default">
                        <div class="panel-body bg-form">
                            <form class="p-2" id="target_form_data" method="POST" action="{{ route('userAccntUpdate') }}">
                                @csrf

                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="lname">Last Name</label>
                                        <input type="text" name="lname" value="{{ $accnt->lname }}" class="form-control form-control-sm" id="lname" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="fname">First Name</label>
                                        <input type="text" name="fname" value="{{ $accnt->fname }}" class="form-control form-control-sm" id="fname" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="mname">Middle Name</label>
                                        <input type="text" name="mname" value="{{ $accnt->mname }}" class="form-control form-control-sm" id="mname" required>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-4">
                                        <label for="position">Position</label>
                                        <select name="position" id="position" class="form-control form-control-sm" required>
                                            <option value="Dean" @if($accnt->position == 'Dean') selected @endif>Dean</option>
                                            <option value="Office Head" @if($accnt->position == 'Office Head') selected @endif>Office Head</option>
                                            <option value="Budget Officer III" @if($accnt->position == 'Budget Officer III') selected @endif>Budget Officer III</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="office">Office</label>
                                        <input type="text" name="office" value="{{ $accnt->office }}" class="form-control form-control-sm" id="office" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="gender">Gender</label>
                                        <select name="gender" id="gender" class="form-control form-control-sm" required>
                                            <option value="Male" @if($accnt->gender == 'Male') selected @endif>Male</option>
                                            <option value="Female" @if($accnt->gender == 'Female') selected @endif>Female</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-4">
                                        <label for="username">Username</label>
                                        <input type="text" name="username" value="{{ $accnt->username }}" class="form-control form-control-sm" id="username" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="password">Password</label>
                                        <input type="password" name="password" class="form-control form-control-sm" id="password" placeholder="Leave blank to keep current">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="password_confirmation">Confirm Password</label>
                                        <input type="password" name="password_confirmation" class="form-control form-control-sm" id="password_confirmation" placeholder="Re-enter password">
                                        <div id="password-error" class="text-danger small mt-1" style="display: none;"></div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-12 text-right">
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="glyphicon glyphicon-save"></i> Save
                                        </button>
                                    </div>
                                </div>

                            </form>
                        </div> <!-- panel-body -->
                    </div> <!-- panel -->
                </div> <!-- card-body -->
            </div> <!-- card -->
        </div>
    </div>
</div>
<script>
    document.getElementById("target_form_data").addEventListener("submit", function(e) {
        const password = document.getElementById("password").value;
        const confirmPassword = document.getElementById("password_confirmation").value;
        const errorDiv = document.getElementById("password-error");

        errorDiv.style.display = "none";
        errorDiv.textContent = "";

        if (password !== '') {
            if (password !== confirmPassword) {
                e.preventDefault();
                errorDiv.textContent = "Passwords do not match.";
                errorDiv.style.display = "block";
            }
        }
    });
</script>
@endsection
