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

<div class="form-group">
    <label for="lokasi">Lokasi</label>
    <input type="text" class="form-control underline" name="lokasi" id="lokasi"
        value="{{ old('lokasi', $item->lokasi ?? '') }}" required>
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

<div class="form-group">
    <label for="total_kejadian">Total Kejadian</label>
    <input type="number" class="form-control underline" name="total_kejadian" id="total_kejadian"
        value="{{ old('total_kejadian', $item->jumlah_kejadian ?? '') }}" required>
</div>

<div class="form-group">
    <label for="jenis_kejadian">Jenis Kejadian</label>
    <input type="text" class="form-control underline" name="jenis_kejadian" id="jenis_kejadian"
        value="{{ old('jenis_kejadian', $item->jenis_kejahatan_dominan ?? '') }}" required>
</div>

<div class="form-group">
    <label for="avg_kerugian">Avg. Kerugian (juta)</label>
    <input type="number" step="0.01" class="form-control underline" name="avg_kerugian" id="avg_kerugian"
        value="{{ old('avg_kerugian', $item->rata_rata_kerugian_juta ?? '') }}" required>
</div>

<div class="form-group">
    <label for="jumlah_penduduk">Jumlah Penduduk</label>
    <input type="number" class="form-control underline" name="jumlah_penduduk" id="jumlah_penduduk"
        value="{{ old('jumlah_penduduk', $item->jumlah_penduduk ?? '') }}" required>
</div>
