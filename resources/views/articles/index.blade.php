@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Artikel</h4>

    @if(isset($articles) && count($articles))
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Kategori</th>
                        <th>Judul</th>
                        <th>Tanggal Publish</th>
                        <th>Dibuat Oleh</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($articles as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">
                                @if(!empty($item->gambar))
                                    <img src="{{ asset('blogs/' . $item->gambar) }}" alt="Gambar" style="max-width: 100px; max-height: 100px;">
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center">{{ ucwords($item->CategoryArtikel?->name ?? 'Tanpa Kategori') }}</td>
                            <td>{{ $item->judul ?? '-' }}</td>
                            <td>
                                @if(!empty($item->publish_date))
                                    {{ \Carbon\Carbon::parse($item->publish_date)->translatedFormat('d F Y') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center">{{ $item->createdBy?->name ?? 'Admin' }}</td>
                            <td class="text-center">
                                @if (($item->status ?? '0') == '0')
                                    <span class="badge bg-danger">Draft</span>
                                @else
                                    <span class="badge bg-success">Published</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $item->views ?? 0 }}</td>
                            <td>
                                <a href="{{ route('article.show', $item->id) }}" class="btn btn-sm btn-info">Detail</a>
                                <a href="{{ route('article.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info">
            Belum ada artikel.
        </div>
    @endif
</div>
@endsection