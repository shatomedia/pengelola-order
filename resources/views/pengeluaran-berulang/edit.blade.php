@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Edit Pengeluaran Berulang</h6>
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
                    <form action="{{ route('pengeluaran-berulang.update', $pengeluaranBerulang->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="nama">Nama Tagihan</label>
                            <input name="nama" type="text" class="form-control" id="nama" value="{{ old('nama', $pengeluaranBerulang->nama) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="kategori_id">Kategori</label>
                            <select name="kategori_id" class="form-control select2" id="kategori_id" required>
                                <option value="">Pilih</option>
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" {{ old('kategori_id', $pengeluaranBerulang->kategori_id) == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="jumlah_estimasi">Estimasi Jumlah (Rp)</label>
                            <input name="jumlah_estimasi" type="number" class="form-control" id="jumlah_estimasi" value="{{ old('jumlah_estimasi', $pengeluaranBerulang->jumlah_estimasi) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_jatuh_tempo">Tanggal Jatuh Tempo (1-28 tiap bulan)</label>
                            <input name="tanggal_jatuh_tempo" type="number" min="1" max="28" class="form-control" id="tanggal_jatuh_tempo" value="{{ old('tanggal_jatuh_tempo', $pengeluaranBerulang->tanggal_jatuh_tempo) }}" required>
                        </div>
                        <div class="form-group form-check">
                            <input type="hidden" name="aktif" value="0">
                            <input type="checkbox" class="form-check-input" name="aktif" id="aktif" value="1" {{ old('aktif', $pengeluaranBerulang->aktif) ? 'checked' : '' }}>
                            <label class="form-check-label" for="aktif">Aktif (ikut di-generate tiap bulan)</label>
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control">{{ old('keterangan', $pengeluaranBerulang->keterangan) }}</textarea>
                        </div>

                        <button type="submit" class="btn bg-gradient-warning"><i class="fas fa-save" aria-hidden="true"></i> Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
