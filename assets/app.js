// Konfirmasi hapus
document.querySelectorAll('.form-hapus').forEach(function (form) {
    form.addEventListener('submit', function (ev) {
        if (!confirm('Hapus buku "' + form.dataset.judul + '"? Data dan file sampul akan dihapus permanen.')) {
            ev.preventDefault();
        }
    });
});

// Validasi & pratinjau sampul di sisi klien (validasi utama tetap di server)
var input = document.getElementById('sampul');
if (input) {
    var preview = document.getElementById('preview');
    var err = document.getElementById('err-file');
    var awal = preview.src;
    input.addEventListener('change', function () {
        err.classList.add('d-none');
        var f = input.files[0];
        if (!f) { preview.src = awal; return; }
        var formatOk = /\.(jpe?g|png)$/i.test(f.name);
        var ukuranOk = f.size <= 2 * 1024 * 1024;
        if (!formatOk || !ukuranOk) {
            err.textContent = !formatOk ? 'Format harus .jpg atau .png.' : 'Ukuran file maksimal 2 MB.';
            err.classList.remove('d-none');
            input.value = '';
            preview.src = awal;
            return;
        }
        preview.src = URL.createObjectURL(f);
    });
}
