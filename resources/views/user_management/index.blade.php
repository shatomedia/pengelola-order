@extends('layouts.master')

@section('content')
    <div>
        <div class="row mb-3">
            @foreach ($roles as $role)
                @php

                    $rolePermissions = \DB::table('role_has_permissions')
                        ->where('role_has_permissions.role_id', $role->id)
                        ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
                        ->all();
                @endphp
                <div class="col-md-4">
                    <div class="card mx-4">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Total
                                            {{ $role->model_has_roles_count }} users</p>
                                        <h5 class="font-weight-bolder mb-0">
                                            {{ $role->name }}
                                        </h5>

                                    </div>
                                    <div>
                                        <!-- Button trigger modal -->
                                        <div data-bs-toggle="modal" data-bs-target="#modalEdit{{ $role->id }}"
                                            style="cursor: pointer">
                                            Edit
                                        </div>

                                        <!-- Modal -->
                                        <div class="modal fade" id="modalEdit{{ $role->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Edit
                                                            {{ $role->name }}</h5>
                                                        <button type="button" class="btn-close text-dark"
                                                            data-bs-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('roles.update', $role->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            @if ($errors->any())
                                                                <div class="alert alert-danger">
                                                                    <ul>
                                                                        @foreach ($errors->all() as $error)
                                                                            <li>{{ $error }}</li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            @endif
                                                            <div class="mb-3">
                                                                <label for="name" class="form-label">Nama</label>
                                                                <input type="text" class="form-control" id="name"
                                                                    name="name" value="{{ $role->name }}">
                                                            </div>
                                                            <div class="col-12">
                                                                <h5>Role Permissions</h5>
                                                                <div class="row">
                                                                    <div class="col-md-3">
                                                                        Akses {{ Str::title($role->name) }}
                                                                        <i class="mdi mdi-information-outline"
                                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                                            aria-label="Allows a full access to the system"
                                                                            data-bs-original-title="Allows a full access to the system"></i>
                                                                    </div>
                                                                    <div class="col-md-9">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="checkbox"
                                                                                id="selectAll-{{ $role->id }}">
                                                                            <label class="form-check-label"
                                                                                for="selectAll-{{ $role->id }}">
                                                                                Pilih
                                                                                Semua </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    @foreach ($categories as $index => $category)
                                                                        <div class="col-md-3">
                                                                            <strong>{{ $category->category }}</strong>
                                                                        </div>
                                                                        <div class="col-md-9">
                                                                            <div class="row">
                                                                                @foreach ($listPermissions[$category->category] as $permission)
                                                                                    <div class="col-md-6">
                                                                                        <div
                                                                                            class="form-check me-3 me-lg-5">
                                                                                            <input class="form-check-input"
                                                                                                type="checkbox"
                                                                                                id="permission-{{ $role->id . $permission->id }}"
                                                                                                @if (in_array($permission->id, $rolePermissions)) checked @endif
                                                                                                name="permission[]"
                                                                                                value="{{ $permission->id }}">
                                                                                            <label class="form-check-label"
                                                                                                for="permission-{{ $role->id . $permission->id }}">
                                                                                                {{ $permission->name }}
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn bg-gradient-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn bg-gradient-primary">Save
                                                                changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                        <i class="ni ni-single-02 text-lg opacity-10" aria-hidden="true"></i>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card mb-4 mx-4">
                    <div class="card-header pb-0">
                        <div class="d-flex flex-row justify-content-between">
                            <div>
                                <h5 class="mb-0">Data Pengguna</h5>
                            </div>
                            <!-- Button trigger modal -->
                            <button type="button" class="btn bg-gradient-warning" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                Tambah
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                            <button type="button" class="btn-close text-dark" data-bs-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('users.store') }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                @if ($errors->any())
                                                    <div class="alert alert-danger">
                                                        <ul>
                                                            @foreach ($errors->all() as $error)
                                                                <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                                <div class="mb-3">
                                                    <label for="role" class="form-label">Role</label>
                                                    <select name="role_id" id="role" class="form-control">
                                                        <option value="">Pilih</option>
                                                        @foreach ($roles as $role)
                                                            <option value="{{ $role->id }}"
                                                                {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                                                {{ $role->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Nama</label>
                                                    <input type="text" class="form-control" id="name"
                                                        name="name" value="{{ old('name') }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" class="form-control" id="email"
                                                        name="email" value="{{ old('email') }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="password" class="form-label">Password</label>
                                                    <input type="password" class="form-control" id="password"
                                                        name="password">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="password_confirmation" class="form-label">Konfirmasi
                                                        Password</label>
                                                    <input type="password" class="form-control"
                                                        id="password_confirmation" name="password_confirmation">

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn bg-gradient-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn bg-gradient-primary">Save
                                                        changes</button>
                                                </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0 table-mobile-cards">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            No
                                        </th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Nama
                                        </th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Email
                                        </th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            role
                                        </th>

                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $nomor => $user)
                                        <tr>
                                            <td class="ps-4" data-label="No">
                                                <p class="text-xs font-weight-bold mb-0">{{ $nomor + 1 }}</p>
                                            </td>

                                            <td class="text-center" data-label="Nama">
                                                <p class="text-xs font-weight-bold mb-0">{{ $user->name }}</p>
                                            </td>
                                            <td class="text-center" data-label="Email">
                                                <p class="text-xs font-weight-bold mb-0">{{ $user->email }}</p>
                                            </td>
                                            <td class="text-center" data-label="Role">
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ Str::title($user->modelHasRole->role->name) }}</p>
                                            </td>
                                            <td class="text-center" data-label="Aksi">

                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn bg-gradient-default"
                                                        style="box-shadow: none" data-bs-toggle="modal"
                                                        data-bs-target="#modalEditUser{{ $user->id }}">
                                                        <i class="cursor-pointer fas fa-user-edit text-secondary"></i>
                                                    </button>
                                                    @if (auth()->user()->can('user-management-delete') && auth()->user()->id != $user->id)
                                                        <button type="submit" class="btn bg-gradient-default"
                                                            style="box-shadow: none"><i
                                                                class="cursor-pointer fas fa-trash text-secondary"></i></button>
                                                    @endif
                                                </form>

                                            </td>
                                        </tr>
                                        @include('user_management.modal_edit_user')
                                    @endforeach


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('custom/user-management/select-all.js') }}"></script>
@endsection
