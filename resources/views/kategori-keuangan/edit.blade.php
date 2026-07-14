@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Edit Kategori Keuangan</h6>
                </div>
                <div class="card-body px-3 pt-0 pb-2">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="/kategori-keuangan/{{ $transactionCategory->id }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="nama_kategori">Nama Kategori</label>
                            <input name="nama_kategori" type="text" class="form-control" id="nama_kategori" value="{{ old('nama_kategori', $transactionCategory->nama_kategori) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="jenis">Jenis</label>
                            <select name="jenis" class="form-control" id="jenis" required>
                                <option value="">Pilih</option>
                                <option value="pemasukan" {{ old('jenis', $transactionCategory->jenis) == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                                <option value="pengeluaran" {{ old('jenis', $transactionCategory->jenis) == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control">{{ old('deskripsi', $transactionCategory->deskripsi) }}</textarea>
                        </div>

                        <button type="submit" class="btn bg-gradient-warning"><i class="fas fa-save" aria-hidden="true"></i> Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
