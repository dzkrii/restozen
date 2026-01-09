# RestoZen - Testing Checklist

> **Tujuan:** Verifikasi semua fitur berjalan dengan benar dari registrasi hingga operasional penuh.

---

## 1. 🚀 Subscription & Registration

### 1.1 Landing Page

-   [ ] Buka http://localhost:8000 → Landing page tampil dengan benar
-   [ ] Navigasi menu (Features, Pricing, Contact) berfungsi
-   [ ] Tombol "Berlangganan Sekarang" mengarah ke halaman pricing

### 1.2 Subscription Flow

-   [ ] Klik salah satu paket (Starter/Professional/Enterprise)
-   [ ] Pilih durasi (Bulanan/Tahunan)
-   [ ] Klik "Pilih Paket" → Redirect ke Midtrans Payment
-   [ ] **[SIMULASI]** Selesaikan pembayaran di Midtrans sandbox
-   [ ] Redirect ke halaman registrasi dengan data subscription terisi

### 1.3 User Registration

-   [ ] Isi form registrasi (Nama, Email, Password, Nama Bisnis, dll)
-   [ ] Klik "Daftar" → Akun dan Company berhasil dibuat
-   [ ] Redirect ke Dashboard

---

## 2. 📊 Dashboard & Navigation

-   [ ] Dashboard menampilkan statistik (Pendapatan, Pesanan, Menu)
-   [ ] Quick Actions berfungsi (Buat Pesanan Baru, Lihat Laporan)
-   [ ] Sidebar menu tampil sesuai dengan capabilities user
-   [ ] Outlet Switcher berfungsi (jika multi-outlet)
-   [ ] Dark/Light mode toggle berfungsi

---

## 3. 🍽️ Menu Management

### 3.1 Kategori Menu

-   [ ] Buka Menu → Kategori
-   [ ] **Create:** Tambah kategori baru (nama + warna)
-   [ ] **Read:** Daftar kategori tampil dengan benar
-   [ ] **Update:** Edit kategori
-   [ ] **Delete:** Hapus kategori

### 3.2 Item Menu

-   [ ] Buka Menu → Item
-   [ ] **Create:** Tambah item baru (nama, harga, kategori, gambar)
-   [ ] **Read:** Daftar item tampil dengan filter kategori
-   [ ] **Update:** Edit item (ubah harga, stok, status)
-   [ ] **Delete:** Hapus item
-   [ ] Toggle status available/unavailable

---

## 4. 🪑 Table Management

### 4.1 Area Meja

-   [ ] Buka Meja → Area
-   [ ] **CRUD:** Tambah, lihat, edit, hapus area

### 4.2 Meja

-   [ ] Buka Meja → Daftar Meja
-   [ ] **Create:** Tambah meja baru (nomor, kapasitas, area)
-   [ ] **Update:** Edit meja
-   [ ] **Delete:** Hapus meja
-   [ ] QR Code meja ter-generate dengan benar
-   [ ] Download QR Code berfungsi

---

## 5. 💰 POS / Cashier

### 5.1 Membuat Pesanan Baru

-   [ ] Buka Pesanan → Buat Pesanan
-   [ ] Pilih meja
-   [ ] POS Menu tampil dengan grid item
-   [ ] Tambah item ke keranjang
-   [ ] Edit qty / hapus item dari keranjang
-   [ ] Input catatan pesanan
-   [ ] Klik "Proses Pesanan" → Order tersimpan

### 5.2 Manajemen Pesanan

-   [ ] Buka Pesanan → Daftar Pesanan
-   [ ] Filter by status (pending, preparing, ready, completed)
-   [ ] Klik pesanan → Detail pesanan tampil
-   [ ] Update status pesanan

### 5.3 Pembayaran

-   [ ] Buka detail pesanan → Proses Pembayaran
-   [ ] Pilih metode (Cash/Card/E-Wallet)
-   [ ] Input jumlah bayar (untuk cash)
-   [ ] Klik Bayar → Status jadi "Paid"
-   [ ] Struk/Receipt tampil dengan benar
-   [ ] Print receipt berfungsi

---

## 6. 🍳 Kitchen Display System (KDS)

-   [ ] Buka Kitchen → Dashboard
-   [ ] Pesanan baru dengan status "pending" tampil
-   [ ] Klik item → Update status ke "preparing"
-   [ ] Klik item lagi → Update status ke "ready"
-   [ ] Semua item ready → Order otomatis "completed"
-   [ ] Sound notification saat order baru (jika ada)

---

## 7. 👥 Employee Management

-   [ ] Buka Karyawan → Daftar Karyawan
-   [ ] **Create:** Tambah karyawan baru
    -   Isi nama, email, phone
    -   Pilih capabilities (checklist)
    -   Generate/input PIN
-   [ ] **Read:** Daftar karyawan tampil dengan role
-   [ ] **Update:** Edit data & capabilities karyawan
-   [ ] **Delete:** Hapus karyawan
-   [ ] Reset PIN karyawan

---

## 8. 📈 Reports

-   [ ] Buka Laporan → Penjualan Harian
-   [ ] Filter tanggal berfungsi
-   [ ] Data penjualan tampil dengan benar
-   [ ] Buka Laporan → Metode Pembayaran
-   [ ] Chart/grafik tampil dengan benar
-   [ ] Buka Laporan → Top Selling
-   [ ] Item terlaris tampil dengan ranking
-   [ ] Export PDF berfungsi (jika ada)

---

## 9. 📱 QR Order (Customer Side)

-   [ ] Scan QR Code meja atau buka URL manual
-   [ ] Menu tampil untuk customer
-   [ ] Tambah item ke keranjang
-   [ ] Submit pesanan
-   [ ] Status pesanan ter-update real-time
-   [ ] Pesanan muncul di KDS

---

## 10. 🔐 Role/Capability Testing

Test akses dengan user berbeda capabilities:

| Capability         | Halaman yang Bisa Diakses   | Halaman yang Diblokir |
| ------------------ | --------------------------- | --------------------- |
| `menu_management`  | Menu→Kategori, Menu→Item    | Karyawan, Laporan     |
| `table_management` | Meja→Area, Meja→Daftar      | Karyawan, Laporan     |
| `cashier`          | Pesanan (semua), Pembayaran | Karyawan, Laporan     |
| `kitchen`          | Kitchen→Dashboard           | Karyawan, Laporan     |
| `employees`        | Karyawan→Daftar             | -                     |
| `reports`          | Laporan→Semua               | -                     |

### Test Scenario:

-   [ ] Login sebagai **Owner** (semua capabilities) → Semua menu tampil
-   [ ] Buat employee dengan **hanya `cashier`** capability
-   [ ] Login sebagai employee tersebut
-   [ ] Verifikasi hanya menu Pesanan yang tampil
-   [ ] Coba akses manual URL `/employees` → Harus ditolak (403)

---

## 11. 🔄 Profile & Account

-   [ ] Buka Profile → Edit Profile
-   [ ] Update nama/email
-   [ ] Ganti password
-   [ ] Logout → Redirect ke landing/login

---

## ✅ Summary Checklist

| Modul                       | Status |
| --------------------------- | ------ |
| Subscription & Registration | ⬜     |
| Dashboard                   | ⬜     |
| Menu Management             | ⬜     |
| Table Management            | ⬜     |
| POS/Cashier                 | ⬜     |
| Kitchen Display             | ⬜     |
| Employee Management         | ⬜     |
| Reports                     | ⬜     |
| QR Order                    | ⬜     |
| Role/Capability             | ⬜     |
| Profile                     | ⬜     |

---

## 🐛 Bug Log

| #   | Modul | Deskripsi Bug | Severity | Status |
| --- | ----- | ------------- | -------- | ------ |
| 1   |       |               |          |        |
| 2   |       |               |          |        |
| 3   |       |               |          |        |

> Severity: 🔴 Critical | 🟠 High | 🟡 Medium | 🟢 Low

---

## Notes

```
Catatan selama testing:
-
-
```
