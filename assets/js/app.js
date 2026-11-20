document.addEventListener('DOMContentLoaded', function () {
    // Tampilkan informasi stok saat memilih bahan baku pada form pengeluaran
    const selectBahan = document.getElementById('selectBahanKeluar');
    const infoStok = document.getElementById('infoStokTersedia');

    if (selectBahan && infoStok) {
        selectBahan.addEventListener('change', function () {
            const selected = this.options[this.selectedIndex];
            const stok = selected.getAttribute('data-stok');
            const satuan = selected.getAttribute('data-satuan');
            if (stok !== null && stok !== '') {
                infoStok.textContent = `Stok tersedia saat ini: ${stok} ${satuan}`;
            } else {
                infoStok.textContent = 'Pilih bahan baku untuk melihat sisa stok.';
            }
        });
    }
});
