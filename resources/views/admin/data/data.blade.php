@extends('layouts.app')

@section('page-title', 'Data Kriminal')

@section('content')


    <div class="container-fluid py-2">
        <div class="row">
            <div class="ms-3">
                <h3 class="mb-0 h4 font-weight-bolder">Data Kriminal</h3>
                <p class="mb-4">
                    Check the sales, value and bounce rate by country.
                </p>
            </div>
            <div class="card">
                <div class="card-header d-flex flex-wrap justify-content-center justify-content-xl-between">
                    <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modalTambah">
                        <i class="fas fa-plus me-2"></i>Tambah Data
                    </button>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead style="background-color: #d3d5d6ff; color: white;" class="text-center">
                                <tr>
                                    <th>Kecamatan</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                    <th>Jenis Kejahatan</th>
                                    <th>Kerugian</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data_kriminal as $item)
                                    <tr class="text-center">
                                        <td>{{ $item->kecamatan }}</td>
                                        <td>{{ $item->latitude }}</td>
                                        <td>{{ $item->longitude }}</td>
                                        <td>{{ $item->jenis_kejahatan }}</td>
                                        <td>{{ $item->kerugian_juta }}</td>
                                        <td>
                                            <button class="btn btn-warning btn-sm" data-toggle="modal"
                                                data-target="#editModal{{ $item->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <!-- Tombol hapus membuka modal -->
                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                data-target="#hapusModal{{ $item->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>

                                            <!-- Modal Konfirmasi Hapus -->
                                            <div class="modal fade" id="hapusModal{{ $item->id }}" tabindex="-1" role="dialog"
                                                aria-labelledby="hapusModalLabel{{ $item->id }}" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            {{-- Judul Modal --}}
                                                            <h5 class="modal-title" id="hapusModalLabel{{ $item->ID }}">
                                                                Konfirmasi Hapus Data</h5>
                                                        </div>

                                                        {{-- Form diletakkan di sini untuk mencakup tombol di footer --}}
                                                        <form action="{{ route('data.destroy', $item->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')

                                                            <div class="modal-body">
                                                                {{-- Isi Pesan Konfirmasi --}}
                                                                <p>Apakah Anda yakin ingin menghapus data untuk Kecamatan:</p>
                                                                <p><strong>{{ $item->kecamatan }}
                                                                        ({{ $item->jenis_kejahatan }})</strong>?</p>
                                                                <p class="text-danger small">Tindakan ini tidak dapat
                                                                    dibatalkan.</p>
                                                            </div>

                                                            <div class="modal-footer">
                                                                {{-- Tombol Aksi --}}
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                                            </div>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-danger">Belum ada data.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $data_kriminal->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>



    {{-- Modal Tambah --}}
    @include('admin.data.tambah')

    {{-- Modal Edit untuk setiap data --}}
    @foreach ($data_kriminal as $item)
        @include('admin.data.edit', ['item' => $item])
    @endforeach
@endsection

@section('scripts')
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 2500,
                showConfirmButton: false
            });
        @elseif (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                timer: 2500,
                showConfirmButton: false
            });
        @endif
    </script>
@endsection