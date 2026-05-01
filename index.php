<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Ambil data restoran
$resto = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM restaurants WHERE id=1"));

// Ambil semua menu & kategori
$kategori_result = mysqli_query($conn, "SELECT * FROM categories");
?>
<?php include 'includes/header.php'; ?>

<div class="text-center mb-4">
    <?php if (!empty($resto['logo'])): ?>
        <img src="<?= htmlspecialchars($resto['logo']) ?>" alt="Logo" height="80">
    <?php endif; ?>
    <h1 class="mt-2"><?= htmlspecialchars($resto['nama_resto']) ?></h1>
    <p><?= nl2br(htmlspecialchars($resto['deskripsi'])) ?></p>
    <p class="text-muted">Kontak: <?= htmlspecialchars($resto['kontak']) ?></p>
</div>

<h2>Menu Kami</h2>
<?php while ($kat = mysqli_fetch_assoc($kategori_result)): ?>
    <h3 class="mt-4"><?= htmlspecialchars($kat['nama_kategori']) ?></h3>
    <div class="row">
        <?php
        $menu_query = mysqli_query($conn, "SELECT * FROM menus WHERE category_id={$kat['id']}");
        while ($menu = mysqli_fetch_assoc($menu_query)):
        ?>
            <div class="col-md-4 mb-3">
                <div class="card menu-card">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($menu['nama_menu']) ?></h5>
                        <p class="card-text"><?= nl2br(htmlspecialchars($menu['deskripsi'])) ?></p>
                        <p class="fw-bold">Rp <?= number_format($menu['harga'], 0, ',', '.') ?></p>
                        <?php
                        // Ambil varian
                        $varian_query = mysqli_query($conn, "SELECT * FROM variants WHERE menu_id={$menu['id']}");
                        if (mysqli_num_rows($varian_query) > 0):
                        ?>
                            <select class="form-select mb-2 variant-select" data-menu-id="<?= $menu['id'] ?>" data-harga="<?= $menu['harga'] ?>">
                                <option value="">Pilih Varian (default)</option>
                                <?php while ($var = mysqli_fetch_assoc($varian_query)): ?>
                                    <option value="<?= $var['id'] ?>" data-tambahan="<?= $var['tambahan_harga'] ?>">
                                        <?= htmlspecialchars($var['nama_varian']) ?> 
                                        <?= $var['tambahan_harga'] > 0 ? '(+Rp ' . number_format($var['tambahan_harga'],0,',','.') . ')' : '' ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        <?php endif; ?>
                        <input type="number" class="form-control mb-2 qty-input" value="1" min="1" style="width:80px;">
                        <button class="btn btn-primary add-to-cart" data-menu-id="<?= $menu['id'] ?>" data-nama="<?= htmlspecialchars($menu['nama_menu']) ?>" data-harga="<?= $menu['harga'] ?>">Pesan</button>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
<?php endwhile; ?>

<!-- Keranjang & form pemesanan (disederhanakan) -->
<div class="card mt-5" id="cart-section" style="display:none;">
    <div class="card-header bg-primary text-white">Pesanan Anda</div>
    <div class="card-body">
        <ul id="cart-items" class="list-group mb-3"></ul>
        <div class="row">
            <div class="col-md-6 mb-2">
                <input type="text" id="nama_pemesan" class="form-control" placeholder="Nama Pemesan">
            </div>
            <div class="col-md-6 mb-2">
                <input type="text" id="meja" class="form-control" placeholder="Nomor Meja">
            </div>
        </div>
        <button id="btn-kirim-pesanan" class="btn btn-success">Kirim Pesanan</button>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
<script>
// Script sederhana untuk menambah item ke keranjang (client-side)
let cart = [];
document.querySelectorAll('.add-to-cart').forEach(btn => {
    btn.addEventListener('click', function(){
        let menuId = this.dataset.menuId;
        let nama = this.dataset.nama;
        let harga = parseFloat(this.dataset.harga);
        let qty = parseInt(this.parentElement.querySelector('.qty-input').value) || 1;
        let variantSelect = this.parentElement.querySelector('.variant-select');
        let variantId = variantSelect ? variantSelect.value : '';
        let variantText = variantSelect ? variantSelect.options[variantSelect.selectedIndex].text : '';
        let tambahan = variantSelect ? parseFloat(variantSelect.options[variantSelect.selectedIndex].dataset.tambahan || 0) : 0;
        
        let totalHarga = (harga + tambahan) * qty;
        cart.push({menuId, nama, harga, tambahan, variantId, variantText, qty, totalHarga});
        renderCart();
    });
});

function renderCart() {
    let cartItems = document.getElementById('cart-items');
    let cartSection = document.getElementById('cart-section');
    cartItems.innerHTML = '';
    if (cart.length === 0) {
        cartSection.style.display = 'none';
        return;
    }
    cartSection.style.display = 'block';
    cart.forEach((item, index) => {
        let li = document.createElement('li');
        li.className = 'list-group-item d-flex justify-content-between align-items-center';
        li.innerHTML = `${item.nama} ${item.variantText} x${item.qty} - Rp ${item.totalHarga.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, '.')} <button class="btn btn-sm btn-danger remove-item" data-index="${index}">X</button>`;
        cartItems.appendChild(li);
    });
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', function(){
            cart.splice(this.dataset.index, 1);
            renderCart();
        });
    });
}

// Kirim pesanan
document.getElementById('btn-kirim-pesanan').addEventListener('click', function(){
    let nama = document.getElementById('nama_pemesan').value.trim();
    let meja = document.getElementById('meja').value.trim();
    if (!nama || !meja) { alert('Isi nama dan meja'); return; }
    if (cart.length === 0) { alert('Keranjang kosong'); return; }
    
    fetch('proses_pesan.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({nama_pemesan: nama, meja: meja, items: cart})
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Pesanan berhasil dikirim!');
            cart = [];
            renderCart();
            document.getElementById('nama_pemesan').value = '';
            document.getElementById('meja').value = '';
        } else {
            alert('Gagal: ' + data.message);
        }
    });
});
</script>