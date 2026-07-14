@extends('layouts.master')

@section('content')
    <div>
        <div class="row">
            <div class="col-12">
                <div class="card mb-4 mx-4">
                    <div class="card-header pb-0">
                        <div class="d-flex flex-row justify-content-between">
                            <div>
                                <h5 class="mb-0">Permission</h5>
                            </div>
                            <!-- Button trigger modal -->
                            <button type="button" class="btn bg-gradient-warning" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                <i class="fas fa-plus" aria-hidden="true"></i> Tambah Permission
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Tambah Permission</h5>
                                            <button type="button" class="btn-close text-dark" data-bs-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('permission.store') }}" method="post">
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
                                                    <label for="name" class="form-label">Nama</label>
                                                    <input type="text" class="form-control" id="name"
                                                        name="name">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="kategori" class="form-label">Kategori</label>
                                                    <select name="category" id="kategori" class="form-control">
                                                        <option value="">Pilih</option>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->category }}"
                                                                {{ old('category') == $category->category ? 'selected' : '' }}>
                                                                {{ $category->category }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="kategori_baru" class="form-label">Kategori Baru</label>
                                                    <input type="text" class="form-control" id="kategori_baru"
                                                        name="kategori_baru">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn bg-gradient-secondary"
                                                    data-bs-dismiss="modal"><i class="fas fa-times" aria-hidden="true"></i> Tutup</button>
                                                <button type="submit" class="btn bg-gradient-primary"><i class="fas fa-save" aria-hidden="true"></i> Simpan</button>
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
                                            Kateogri
                                        </th>

                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($permissions as $no => $permission)
                                        <tr>
                                            <td data-label="No">
                                                <p class="text-sm font-weight-bold mb-0 px-3">{{ $no + 1 }}</p>
                                            </td>
                                            <td data-label="Nama">
                                                <p class="text-center text-sm font-weight-bold mb-0">{{ $permission->name }}
                                                </p>
                                            </td>
                                            <td data-label="Kategori">
                                                <p class="text-center text-sm font-weight-bold mb-0">
                                                    {{ $permission->category }}</p>
                                            </td>
                                            <td data-label="Aksi">
                                                <div class="d-flex flex-wrap justify-content-center action-buttons">
                                                    <button type="button" class="btn bg-gradient-warning btn-sm mx-1"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalEdit{{ $permission->id }}">
                                                        <i class="fas fa-edit" aria-hidden="true"></i> Edit
                                                    </button>
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="modalEdit{{ $permission->id }}"
                                                        tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Edit
                                                                        Permission</h5>
                                                                    <button type="button" class="btn-close text-dark"
                                                                        data-bs-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form
                                                                    action="{{ route('permission.update', $permission->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('PUT')

                                                                    <div class="modal-body">
                                                                        <div class="mb-3">
                                                                            <label for="name"
                                                                                class="form-label">Nama</label>
                                                                            <input type="text" class="form-control"
                                                                                id="name" name="name"
                                                                                value="{{ $permission->name }}">
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label for="kategori"
                                                                                class="form-label">Kategori</label>
                                                                            <select name="category" id="kategori"
                                                                                class="form-control">
                                                                                <option value="">Pilih</option>
                                                                                @foreach ($categories as $category)
                                                                                    <option
                                                                                        value="{{ $category->category }}"
                                                                                        {{ $permission->category == $category->category ? 'selected' : '' }}>
                                                                                        {{ $category->category }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button"
                                                                            class="btn bg-gradient-secondary"
                                                                            data-bs-dismiss="modal"><i class="fas fa-times" aria-hidden="true"></i> Tutup</button>
                                                                        <button type="submit"
                                                                            class="btn bg-gradient-primary"><i class="fas fa-save" aria-hidden="true"></i> Simpan</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <form action="{{ route('permission.destroy', $permission->id) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn bg-gradient-danger btn-sm mx-1"><i class="fas fa-trash" aria-hidden="true"></i> Hapus</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="justify-content-center pagination">
                    {{ $permissions->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
