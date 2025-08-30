@extends('layouts.master')

@section('body')
<style>
    .bg-form{
        background-color:  #e9ecef;
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
    .btn-sm{
        font-size: 10px !important;
        height: 25px !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }
    .bb{
        border-bottom: 1px solid rgb(145, 138, 138);
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title text-gray">
                        <b>USER LIST</b>
                    </h2>
                </div>
                <div class="card-body"> 
                    <div class="row">
                        <!-- User Form Column -->
                        <div class="col-lg-4 col-md-12">
                            <div class="card">
                                <div class="card-header">ADD USER</div>
                                <div class="card-body bg-form">
                                    <form class="p-2" id="target_form_data" method="POST" enctype="multipart/form-data" 
                                        action="{{ isset($useredit) ? route('userUpdate', $useredit->id) : route('userCreate') }}">
                                        @csrf
                                        @if(isset($useredit))
                                            <input type="hidden" name="id" value="{{ $useredit->id }}">
                                        @endif

                                        <div class="form-group">
                                            <label for="lname">Last Name</label>
                                            <input type="text" name="lname" 
                                                value="{{ $useredit->lname ?? '' }}" 
                                                class="form-control form-control-sm" id="lname" required>

                                            <label for="fname">First Name</label>
                                            <input type="text" name="fname" 
                                                value="{{ $useredit->fname ?? '' }}" 
                                                class="form-control form-control-sm" id="fname" required>
                                            
                                            <label for="mname">Middle Name</label>
                                            <input type="text" name="mname" 
                                                value="{{ $useredit->mname ?? '' }}" 
                                                class="form-control form-control-sm" id="mname" required>

                                            <label for="gender">Gender</label>
                                            <select name="gender" id="gender" class="form-control form-control-sm" required>
                                                <option value="">-- Select Gender --</option>
                                                <option value="Male" {{ ($useredit->gender ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                                                <option value="Female" {{ ($useredit->gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                                            </select>

                                            <label for="role">Role</label>
                                            <select name="role" id="role" class="form-control form-control-sm">
                                                <option value="2" {{ ($useredit->role ?? '') == '2' ? 'selected' : '' }}>Cashier</option>
                                                <option value="1" {{ ($useredit->role ?? '') == '1' ? 'selected' : '' }}>Administrator</option>
                                            </select>

                                            <label for="username">Username</label>
                                            <input type="text" name="username" 
                                                value="{{ $useredit->username ?? '' }}" 
                                                class="form-control form-control-sm" id="username" required>

                                            <label for="password">Password</label>
                                            <input type="password" name="password" 
                                                class="form-control form-control-sm" id="password" 
                                                {{ isset($useredit) ? '' : 'required' }}> 
                                            {{-- password only required on create --}}

                                            <label for="profile">Profile Picture</label>
                                            <input type="file" name="profile" class="form-control form-control-sm" id="profile" accept="image/*">
                                        </div>

                                        <button type="submit" class="btn bg-main-7 text-light w-100">
                                            <i class="fas fa-save"></i> {{ isset($useredit) ? 'Update' : 'Save' }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- User Table Column -->
                        <div class="col-lg-8 col-md-12">
                            <div class="card">
                                <div class="card-header">USER LIST</div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="example3" class="table table-bordered table-hover" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Profile</th>
                                                    <th>Full Name</th>
                                                    <th>Gender</th>
                                                    <th>Role</th>
                                                    <th>Username</th>
                                                    <th class="text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($users as $user)
                                                <tr id="row-{{ $user->id }}" class="odd gradeX">
                                                    <td class="text-center">
                                                        <img src="{{ $user->profile && Storage::disk('public')->exists('uploads/profile/' . $user->profile) 
                                                                    ? asset('storage/uploads/profile/' . $user->profile) 
                                                                    : asset('storage/uploads/profile/admin-default.png') }}"
                                                            alt="Profile"
                                                            class="img-thumbnail"
                                                            style="width:40px; height:40px; object-fit:cover; border-radius:50%;">
                                                    </td>
                                                    <td>{{ $user->fname }} {{ $user->lname }}</td>
                                                    <td>{{ $user->gender }}</td>    
                                                    <td>
                                                        @if ($user->role == 1)
                                                            <span class="label label-danger">Administrator</span>
                                                        @elseif($user->role == 2)
                                                            <span class="label label-primary">Cashier</span>
                                                        @endif
                                                    </td>
                                                    <td class="center">{{ $user->username }}</td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-info btn-sm edit-btn" data-id="{{ $user->id }}">
                                                            <i class="fas fa-info-circle"></i>
                                                        </button>
                                                        <button value="{{ $user->id }}" class="btn btn-danger btn-sm delete-row" data-model="User" data-id="{{ $user->id }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<form id="post-form" action="{{ route('userEdit') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="id" id="id">
</form>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".edit-btn").forEach(function (btn) {
            btn.addEventListener("click", function () {
                let userId = this.getAttribute("data-id");
                document.getElementById("id").value = userId;
                document.getElementById("post-form").submit();
            });
        });
    });
</script>
@endsection