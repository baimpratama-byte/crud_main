/* Interaksi sisi klien: debounce pencarian, konfirmasi hapus, loading state, validasi pesan, pratinjau foto. */
document.addEventListener('DOMContentLoaded', function () {

    // 1) Debounce pada pencarian: kirim otomatis 400 ms setelah berhenti mengetik
    var searchInput = document.getElementById('searchInput');
    var filterForm = document.getElementById('filterForm');
    if (searchInput && filterForm) {
        var timer;
        searchInput.addEventListener('input', function () {
            clearTimeout(timer);
            timer = setTimeout(function () { filterForm.submit(); }, 400);
        });
        var cat = document.getElementById('categoryFilter');
        if (cat) { cat.addEventListener('change', function () { filterForm.submit(); }); }
        // Pertahankan kursor di akhir teks setelah halaman dimuat ulang
        if (searchInput.value) {
            searchInput.focus();
            searchInput.setSelectionRange(searchInput.value.length, searchInput.value.length);
        }
    }

    // 2) Dialog konfirmasi sebelum menghapus
    var modalEl = document.getElementById('deleteModal');
    if (modalEl && window.bootstrap) {
        var modal = new bootstrap.Modal(modalEl);
        document.querySelectorAll('.btn-delete').forEach(function (btn) {
            btn.addEventListener('click', function () {
                document.getElementById('deleteForm').action = btn.dataset.action;
                document.getElementById('deleteName').textContent = btn.dataset.name;
                modal.show();
            });
        });
    }

    // 3) Loading state: nonaktifkan tombol submit + tampilkan spinner agar tidak terkirim ganda
    document.querySelectorAll('form[data-loading]').forEach(function (form) {
        form.addEventListener('submit', function () {
            if (!form.checkValidity()) { return; }
            var btn = form.querySelector('button[type="submit"]');
            if (!btn) { return; }
            var spinner = btn.querySelector('.spinner-border');
            if (spinner) { spinner.classList.remove('d-none'); }
            setTimeout(function () { btn.disabled = true; }, 0);
        });
    });

    // 4) Pesan validasi berbahasa Indonesia
    document.querySelectorAll('form[data-validate] input, form[data-validate] select').forEach(function (el) {
        el.addEventListener('invalid', function () {
            var v = el.validity, label = (el.closest('.mb-3') || document).querySelector('label');
            var name = label ? label.childNodes[0].textContent.trim() : 'Kolom ini';
            var msg = '';
            if (v.valueMissing) { msg = name + ' wajib diisi.'; }
            else if (v.rangeUnderflow) { msg = name + ' tidak boleh negatif.'; }
            else if (v.badInput || v.typeMismatch) { msg = name + ' tidak valid (harus berupa angka/tanggal yang benar).'; }
            else if (v.stepMismatch) { msg = name + ' harus berupa bilangan bulat.'; }
            el.setCustomValidity(msg);
        });
        el.addEventListener('input', function () { el.setCustomValidity(''); });
        el.addEventListener('change', function () { el.setCustomValidity(''); });
    });

    // 5) Pratinjau foto sebelum diunggah
    var photo = document.getElementById('photo');
    var preview = document.getElementById('photoPreview');
    if (photo && preview) {
        photo.addEventListener('change', function () {
            var file = photo.files && photo.files[0];
            if (!file) { return; }
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('d-none');
        });
    }
});
