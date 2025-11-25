
<div class="form-group">
    <label for="kecamatan">Lokasi</label>
    <input type="text" class="form-control underline" name="kecamatan" id="kecamatan"
        value="{{ old('kecamatan', $item->kecamatan ?? '') }}" required>
</div>

<div class="form-group">
    <label for="latitude">Latitude</label>
    <input type="text" class="form-control underline" name="latitude" id="latitude"
        value="{{ old('latitude', $item->latitude ?? '') }}" required>
</div>

<div class="form-group">
    <label for="longitude">Longitude</label>
    <input type="text" class="form-control underline" name="longitude" id="longitude"
        value="{{ old('longitude', $item->longitude ?? '') }}" required>
</div>

<!-- <div class="form-group">
    <label for="total_kejadian">Total Kejadian</label>
    <input type="number" class="form-control underline" name="total_kejadian" id="total_kejadian"
        value="{{ old('total_kejadian', $item->jumlah_kejadian ?? '') }}" required>
</div> -->

<div class="form-group">
    <label for="jenis_kejahatan">Jenis Kejahatan</label>
    <input type="text" class="form-control underline" name="jenis_kejahatan" id="jenis_kejahatan"
        value="{{ old('jenis_kejahatan', $item->jenis_kejahatan ?? '') }}" required>
</div>

<div class="form-group">
    <label for="kerugian">Kerugian</label>
    <input type="number" step="0.01" class="form-control underline" name="kerugian" id="kerugian"
        value="{{ old('kerugian', $item->kerugian ?? '') }}" required>

<!-- <div class="form-group">
    <label for="jumlah_penduduk">Jumlah Penduduk</label>
    <input type="number" class="form-control underline" name="jumlah_penduduk" id="jumlah_penduduk"
        value="{{ old('jumlah_penduduk', $item->jumlah_penduduk ?? '') }}" required>
</div> -->

<style>
    .form-group {
        padding-bottom: 1rem;
        margin-bottom: 1.25rem;
        border-bottom: 1px solid #ddd;
    }

    .form-group label {
        font-weight: 600;
        display: block;
        margin-bottom: 0.5rem;
    }

    .form-control.underline {
        border: none;
        border-bottom: 2px solid #ccc;
        border-radius: 0;
        outline: none;
        box-shadow: none;
        padding-left: 0;
        background-color: transparent;
        transition: border-color 0.3s ease-in-out;
    }

    .form-control.underline:focus {
        border-bottom: 2px solid #3b82f6;
        background-color: transparent;
    }
</style>
