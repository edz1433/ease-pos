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
                            <div class="panel panel-default">
                                <div class="panel-heading">ADD USER</div>
                                <div class="panel-body bg-form">
                                    <form class="p-2" id="target_form_data" method="POST" action="{{ isset($useredit) ? route('userUpdate') : route('userCreate') }}">
                                        @csrf
                                        <div class="form-group">
                                            @if(isset($useredit))
                                                <input type="hidden" name="id" value="{{ $useredit->id }}">
                                            @endif

                                            <label for="lname">Last Name</label>
                                            <input type="text" name="lname" value="{{ isset($useredit) ? $useredit->lname : '' }}" class="form-control form-control-sm" id="lname" required>

                                            <label for="fname">First Name</label>
                                            <input type="text" name="fname" value="{{ isset($useredit) ? $useredit->fname : '' }}" class="form-control form-control-sm" id="fname" required>
                                            
                                            <label for="mname">Middle Name</label>
                                            <input type="text" name="mname" value="{{ isset($useredit) ? $useredit->mname : '' }}" class="form-control form-control-sm" id="mname" required>

                                            <label for="role">Role</label>
                                            <select name="role" id="role" class="form-control form-control-sm" required>
                                                <option value="1" @if(isset($useredit) && $useredit->role == '1') selected @endif>Administrator</option>
                                                <option value="2" @if(isset($useredit) && $useredit->role == '2') selected @endif>Cashier</option>
                                            </select>

                                            <label for="username">Username</label>
                                            <input type="text" name="username" value="{{ isset($useredit) ? $useredit->username : '' }}" class="form-control form-control-sm" id="username" required>

                                            @if(!isset($useredit))
                                                <label for="password">Password</label>
                                                <input type="password" name="password" class="form-control form-control-sm" id="password" required>
                                            @endif
                                        </div>

                                        <button type="submit" class="btn btn-success ">
                                            <i class="glyphicon glyphicon-save"></i> Save
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- User Table Column -->
                        <div class="col-lg-8 col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">USER LIST</div>
                                <div class="panel-body">
                                    <table id="example3" width="100%" class="table table-striped table-bordered table-hover" id="tables">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Full Name</th>
                                                <th>Position</th>
                                                <th>Office/College</th>
                                                <th>Date created</th>
                                                <th>Role</th>
                                                <th>Username</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no = 1; @endphp
                                            @foreach ($users as $user)
                                            <tr id="row-{{ $user->id }}" class="odd gradeX">
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $user->fname }} {{ $user->lname }}</td>
                                                <td class="center">{{ $user->position }}</td>
                                                <td class="center">{{ $user->office }}</td>
                                                <td>{{ $user->created_at->diffForHumans() }}</td>
                                                <td>
                                                    @if ($user->isAdmin == 0)
                                                        <span class="label label-danger">Administrator</span>
                                                    @elseif($user->isAdmin == 1)
                                                        <span class="label label-primary">User</span>
                                                    @else
                                                        <span class="label label-warning">Budget Officer</span>
                                                    @endif
                                                </td>
                                                <td class="center">{{ $user->username }}</td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-primary btn-sm" onclick="editPostForm({{ $user->id }})">
                                                        <i class="fas fa-info-circle"></i>
                                                    </button>
                                                    <button value="{{ $user->id }}" class="btn btn-danger btn-sm user-delete">
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
<form id="post-form" action="{{ route('userEdit') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="id" id="user-id">
</form>
<script>
    function editPostForm(id) {
        document.getElementById('user-id').value = id;

        document.getElementById('post-form').submit();
    }
</script>
@endsection