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
                                    <th>Lokasi</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                    <th>Total Kejadian</th>
                                    <th>Jenis Kejadian</th>
                                    <th>Avg. Kerugian</th>
                                    <th>Jumlah Penduduk</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data_kriminal as $item)
                                    <tr class="text-center">
                                        <td>{{ $item->lokasi }}</td>
                                        <td>{{ $item->latitude }}</td>
                                        <td>{{ $item->longitude }}</td>
                                        <td>{{ $item->jumlah_kejadian }}</td>
                                        <td>{{ $item->jenis_kejahatan_dominan }}</td>
                                        <td>{{ $item->rata_rata_kerugian_juta }}</td>
                                        <td>{{ $item->jumlah_penduduk }}</td>
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
                                                        <form action="{{ route('data.destroy', $item->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <div class="modal-header bg-danger text-white">
                                                                <h5 class="modal-title" id="hapusModalLabel{{ $item->id }}">
                                                                    Konfirmasi
                                                                    Hapus</h5>
                                                            </div>
                                                            <div class="modal-body">
                                                                Apakah Anda yakin ingin menghapus data kriminal
                                                                <strong>{{ $item->lokasi }}</strong>?
                                                            </div>
                                                            <div class="modal-footer">
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