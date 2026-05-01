-- Struktur tabel untuk My Restaurant

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE restaurants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_resto VARCHAR(100) DEFAULT 'My Restaurant',
    logo VARCHAR(255) DEFAULT NULL,
    deskripsi TEXT DEFAULT NULL,
    kontak VARCHAR(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert data restoran awal
INSERT INTO restaurants (id, nama_resto, logo, deskripsi, kontak) VALUES
(1, 'My Restaurant', NULL, 'Restoran terbaik dengan menu lezat.', '0812-3456-7890');

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO categories (nama_kategori) VALUES ('Makanan'), ('Minuman');

CREATE TABLE menus (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    nama_menu VARCHAR(100) NOT NULL,
    harga DECIMAL(10,2) NOT NULL DEFAULT 0,
    deskripsi TEXT,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE variants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    menu_id INT NOT NULL,
    nama_varian VARCHAR(50) NOT NULL,
    tambahan_harga DECIMAL(10,2) NOT NULL DEFAULT 0,
    FOREIGN KEY (menu_id) REFERENCES menus(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    restaurant_id INT DEFAULT 1,
    nama_pemesan VARCHAR(100) NOT NULL,
    meja VARCHAR(20) NOT NULL,
    total_harga DECIMAL(10,2) NOT NULL DEFAULT 0,
    status ENUM('pending','diproses','selesai') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    menu_id INT NOT NULL,
    variant_id INT DEFAULT NULL,
    jumlah INT NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (menu_id) REFERENCES menus(id),
    FOREIGN KEY (variant_id) REFERENCES variants(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;