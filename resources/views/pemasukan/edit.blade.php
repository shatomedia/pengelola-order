@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Edit Pemasukan</h6>
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
                    <form action="{{ route('pemasukan.update', $pemasukan->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="kategori_id">Kategori</label>
                            <select name="kategori_id" class="form-control select2" id="kategori_id" required>
                                <option value="">Pilih</option>
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" {{ old('kategori_id', $pemasukan->kategori_id) == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="sumber">Sumber</label>
                            <input name="sumber" type="text" class="form-control" id="sumber" value="{{ old('sumber', $pemasukan->sumber) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="order_id">Order Terkait (opsional)</label>
                            <input name="order_id" type="number" class="form-control" id="order_id" value="{{ old('order_id', $pemasukan->order_id) }}">
                        </div>
                        <div class="form-group">
                            <label for="jumlah">Jumlah (Rp)</label>
                            <input name="jumlah" type="number" class="form-control" id="jumlah" value="{{ old('jumlah', $pemasukan->jumlah) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input name="tanggal" type="text" class="form-control datepicker" id="tanggal" value="{{ old('tanggal', $pemasukan->tanggal) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control">{{ old('keterangan', $pemasukan->keterangan) }}</textarea>
                        </div>

                        <button type="submit" class="btn bg-gradient-warning">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
