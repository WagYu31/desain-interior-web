# Tabel Skenario Pengujian White Box (Unit Testing)
## Aplikasi Web Desain Interior

---

### Tabel 4.14 — Skenario Pengujian *White Box (Unit Testing)*

| ID | Unit/Fungsi yang Diuji | Jalur Logika (Branch/Path) | Data Uji (Contoh) | Output yang Diharapkan |
|----|------------------------|----------------------------|--------------------|------------------------|
| **WB-01** | `AuthenticatedSessionController::store()` — Login | Identifier atau password kosong | `{ email:"", password:"" }` | 422 Validation Error — field email & password required |
| **WB-02** | `AuthenticatedSessionController::store()` — Login | Email atau password salah (credential tidak valid) | `{ email:"wrong@mail.com", password:"salah123" }` | 422 — "These credentials do not match our records" |
| **WB-03** | `AuthenticatedSessionController::store()` — Login | Login berhasil sebagai admin/owner/arsitek | `{ email:"admin@mail.com", password:"Password1!" }` | Redirect ke `route('admin.dashboard')` |
| **WB-04** | `AuthenticatedSessionController::store()` — Login | Login berhasil sebagai user biasa | `{ email:"user@mail.com", password:"Password1!" }` | Redirect ke `route('user.dashboard')` |
| **WB-05** | `AuthenticatedSessionController::destroy()` — Logout | User melakukan logout | Session aktif | Session invalidated, redirect ke `/` |
| **WB-06** | `RegisteredUserController::store()` — Register | Semua field kosong | `{ name:"", email:"", phone:"", password:"" }` | 422 Validation Error — semua field required |
| **WB-07** | `RegisteredUserController::store()` — Register | Email sudah terdaftar (duplikat) | `{ name:"Test", email:"existing@mail.com", phone:"081234567890", password:"Pass1!abc", password_confirmation:"Pass1!abc" }` | 422 — "The email has already been taken" |
| **WB-08** | `RegisteredUserController::store()` — Register | Password tidak memenuhi complexity (tanpa simbol) | `{ name:"Test", email:"new@mail.com", phone:"081234567890", password:"Password1", password_confirmation:"Password1" }` | 422 — "The password must contain at least one symbol" |
| **WB-09** | `RegisteredUserController::store()` — Register | Password confirmation tidak cocok | `{ name:"Test", email:"new@mail.com", phone:"081234567890", password:"Pass1!abc", password_confirmation:"Beda1!abc" }` | 422 — "The password confirmation does not match" |
| **WB-10** | `RegisteredUserController::store()` — Register | Registrasi berhasil, nomor HP diawali `0` → diubah ke `62` | `{ name:"Test User", email:"new@mail.com", phone:"081234567890", password:"Pass1!abc", password_confirmation:"Pass1!abc" }` | 201 — User dibuat, phone tersimpan `6281234567890`, role `User` di-assign, redirect ke `user.dashboard` |
| **WB-11** | `RegisteredUserController::store()` — Register | Role 'User' tidak ditemukan di database | Data valid, tapi tabel `roles` kosong | User tetap dibuat, error di-log: "Role 'User' not found" |
| **WB-12** | `PasswordResetLinkController::store()` — Forgot Password | Email kosong | `{ email:"" }` | 422 Validation Error — email required |
| **WB-13** | `PasswordResetLinkController::store()` — Forgot Password | Email valid terdaftar, link terkirim | `{ email:"user@mail.com" }` | Redirect back `with('status', 'passwords.sent')` |
| **WB-14** | `NewPasswordController::store()` — Reset Password | Token tidak valid / expired | `{ token:"invalid-token", email:"user@mail.com", password:"NewPass1!", password_confirmation:"NewPass1!" }` | Redirect back — "This password reset token is invalid" |
| **WB-15** | `NewPasswordController::store()` — Reset Password | Reset berhasil dengan token valid | `{ token:"valid-token", email:"user@mail.com", password:"NewPass1!", password_confirmation:"NewPass1!" }` | Redirect ke `route('login')` dengan status success |
| **WB-16** | `ProfileController::update()` — Update Profile | Email diubah (isDirty) | `{ name:"Updated", email:"newemail@mail.com" }` | `email_verified_at` di-set null, profil tersimpan |
| **WB-17** | `ProfileController::update()` — Update Profile | Nama diubah, email tetap | `{ name:"New Name", email:"same@mail.com" }` | Profil diupdate, `email_verified_at` tetap |
| **WB-18** | `ProfileController::updatePhoto()` — Upload Foto Profil | File bukan gambar (PDF) | `{ profile_photo: file.pdf }` | 422 — "The profile photo must be an image" |
| **WB-19** | `ProfileController::updatePhoto()` — Upload Foto Profil | Upload foto valid, foto lama ada → dihapus | `{ profile_photo: valid.jpg }` (user sudah punya foto) | Foto lama dihapus dari storage, foto baru tersimpan |
| **WB-20** | `ProfileController::updatePhoto()` — Upload Foto Profil | Upload foto valid, tidak ada foto lama | `{ profile_photo: valid.jpg }` (user belum punya foto) | Foto baru tersimpan, tidak ada penghapusan |
| **WB-21** | `ProfileController::destroy()` — Hapus Akun | Password salah | `{ password:"salahpassword" }` | 422 — "The password is incorrect" |
| **WB-22** | `ProfileController::destroy()` — Hapus Akun | Password benar, user memiliki foto profil | `{ password:"correctpassword" }` | Foto profil dihapus, user dihapus, session invalidated, redirect `/` |
| **WB-23** | `User\OrderController::store()` — Buat Order (User) | Field wajib kosong | `{ client_type:"", property_type:"", name:"", phone:"", province:"", city:"", district:"", address:"" }` | 422 Validation Error — semua field wajib required |
| **WB-24** | `User\OrderController::store()` — Buat Order (User) | `client_type` tidak valid (bukan Residensial/Bisnis) | `{ client_type:"Invalid", ... }` | 422 — "The selected client type is invalid" |
| **WB-25** | `User\OrderController::store()` — Buat Order (User) | Order berhasil dibuat (tipe Residensial, dengan design_type & notes) | `{ client_type:"Residensial", property_type:"Rumah", name:"John", phone:"081234567890", province:"Sumut", city:"Medan", district:"Medan Kota", address:"Jl. Test", design_type:["Modern","Minimalis"], room_count:"3", notes:"Catatan" }` | 200 JSON `{ success:true, whatsapp_url:"https://wa.me/..." }`, Order & OrderDetail tersimpan, notifikasi terkirim |
| **WB-26** | `User\OrderController::store()` — Buat Order (User) | Order berhasil dibuat (tipe Bisnis, tanpa optional fields) | `{ client_type:"Bisnis", property_type:"Kantor", name:"Corp", phone:"081234567890", province:"Sumut", city:"Medan", district:"Medan Kota", address:"Jl. Office" }` | 200 JSON `{ success:true }`, field `design_type`, `notes`, `room_count` null |
| **WB-27** | `User\OrderController::cancel()` — Batalkan Order | Reason terlalu pendek (<10 karakter) | `{ cancellation_reason:"pendek" }` | 422 — "The cancellation reason must be at least 10 characters" |
| **WB-28** | `User\OrderController::cancel()` — Batalkan Order | Pembatalan berhasil dengan alasan valid | `{ cancellation_reason:"Saya ingin membatalkan pesanan ini karena alasan tertentu" }` | OrderDetail 'cancelled' dibuat, redirect `user.orders.index` dengan pesan sukses |
| **WB-29** | `User\OrderController::cancel()` — Batalkan Order | User mencoba membatalkan order milik orang lain | User ID ≠ Order `user_id` | 403 Forbidden — Unauthorized |
| **WB-30** | `PaymentController::create()` — Halaman Pembayaran | User bukan pemilik order | Order `user_id` ≠ Auth `id` | 403 — "Unauthorized" |
| **WB-31** | `PaymentController::create()` — Halaman Pembayaran | Sudah ada pending payment dengan snap_token | Order milik user, pending payment exists | View `user.orders.payment` dengan `snapToken` dari payment lama |
| **WB-32** | `PaymentController::create()` — Halaman Pembayaran | Belum ada pending payment, transaksi Midtrans berhasil | Order milik user, tidak ada pending payment | View `user.orders.payment` dengan `snapToken` baru dari Midtrans |
| **WB-33** | `PaymentController::create()` — Halaman Pembayaran | Belum ada pending payment, transaksi Midtrans gagal | Midtrans return `{ success:false }` | Redirect back dengan error "Gagal membuat pembayaran" |
| **WB-34** | `PaymentController::notification()` — Webhook Midtrans | Signature key tidak valid | `{ signature_key:"invalid_hash", order_id:"ORDER-1-xxx", status_code:"200", gross_amount:"500000" }` | 403 JSON `{ message:"Invalid signature" }` |
| **WB-35** | `PaymentController::notification()` — Webhook Midtrans | Signature key valid | Notification data dengan hash SHA512 valid | 200 JSON `{ message:"OK" }`, `handleNotification()` dipanggil |
| **WB-36** | `PaymentController::finish()` — Redirect selesai bayar | `transaction_status` = settlement | `{ order_id:"ORDER-1-xxx", transaction_status:"settlement" }` | Payment status → `success`, `paid_at` di-set, redirect ke order show dengan pesan sukses |
| **WB-37** | `PaymentController::finish()` — Redirect selesai bayar | `transaction_status` = pending | `{ order_id:"ORDER-1-xxx", transaction_status:"pending" }` | Payment status → `pending`, redirect dengan pesan "sedang diproses" |
| **WB-38** | `PaymentController::finish()` — Redirect selesai bayar | `transaction_status` = deny/cancel/expire | `{ order_id:"ORDER-1-xxx", transaction_status:"deny" }` | Payment status → `failed`, redirect dengan pesan "dibatalkan atau gagal" |
| **WB-39** | `PaymentController::finish()` — Redirect selesai bayar | `order_id` kosong / null | `{ order_id: null }` | Redirect ke `user.dashboard` dengan pesan "Proses pembayaran selesai" |
| **WB-40** | `FeedbackController::store()` — Kirim Feedback | User bukan pemilik order | Order `user_id` ≠ Auth `id` | 403 — "Unauthorized" |
| **WB-41** | `FeedbackController::store()` — Kirim Feedback | Order belum berstatus completed | `latestDetail.status` ≠ `completed` | Redirect back — "Anda hanya bisa memberikan feedback untuk pesanan yang sudah selesai" |
| **WB-42** | `FeedbackController::store()` — Kirim Feedback | Feedback sudah pernah diberikan | `$order->feedback` exists | Redirect back — "Anda sudah memberikan feedback untuk pesanan ini" |
| **WB-43** | `FeedbackController::store()` — Kirim Feedback | Rating tidak valid (0 atau 6) | `{ rating:0, review:"Test" }` | 422 — "The rating must be at least 1" |
| **WB-44** | `FeedbackController::store()` — Kirim Feedback | Feedback berhasil disimpan | `{ rating:5, review:"Sangat memuaskan", would_recommend:true }` | Redirect back — "Terima kasih! Feedback Anda telah disimpan" |
| **WB-45** | `Admin\CategoryController::store()` — Tambah Kategori | Nama kosong | `{ name:"" }` | 422 — "The name field is required" |
| **WB-46** | `Admin\CategoryController::store()` — Tambah Kategori | Nama duplikat | `{ name:"Kategori Existing", description:"Test" }` | 422 — "The name has already been taken" |
| **WB-47** | `Admin\CategoryController::store()` — Tambah Kategori | Data valid, slug auto-generated | `{ name:"Modern Minimalis", description:"Desain modern" }` | Kategori tersimpan, `slug` = "modern-minimalis", redirect ke index |
| **WB-48** | `Admin\CategoryController::update()` — Edit Kategori | Nama diubah ke nama yang sudah ada (milik kategori lain) | `{ name:"Nama Kategori Lain" }` (duplikat) | 422 — "The name has already been taken" |
| **WB-49** | `Admin\CategoryController::update()` — Edit Kategori | Nama tetap sama (unique ignore self) | `{ name:"Nama Sama" }` (nama asli) | 200 — Kategori diupdate, slug di-regenerate |
| **WB-50** | `Admin\CategoryController::destroy()` — Hapus Kategori | Kategori valid dihapus | Category ID valid | Kategori dihapus, redirect ke index dengan pesan sukses |
| **WB-51** | `Admin\ProjectController::store()` — Tambah Proyek | Tanpa gambar (images kosong) | `{ title:"Proyek A", category_id:1, project_date:"2026-01-01", description:"Desc" }` (tanpa images) | 422 — "The images field is required" |
| **WB-52** | `Admin\ProjectController::store()` — Tambah Proyek | Data valid dengan gambar, featured_image di-set | `{ title:"Proyek A", category_id:1, project_date:"2026-01-01", description:"Desc", images:[img1,img2], featured_image:0 }` | Proyek & gambar tersimpan, gambar index 0 `is_featured=true` |
| **WB-53** | `Admin\ProjectController::update()` — Edit Proyek | Update tanpa gambar baru, featured image lama dipilih | `{ ..., existing_featured_image:5 }` (tanpa images baru) | `existing_featured_image` di-set `is_featured=true` |
| **WB-54** | `Admin\ProjectController::update()` — Edit Proyek | Update dengan gambar baru, index baru di-set featured | `{ ..., images:[newImg], featured_image_index:0 }` | Semua gambar lama `is_featured=false`, gambar baru `is_featured=true` |
| **WB-55** | `Admin\ProjectController::update()` — Edit Proyek | Tidak ada featured dipilih & tidak ada gambar baru → gambar pertama jadi featured | `{ ..., existing_featured_image:null }` (tanpa gambar baru) | Gambar pertama otomatis `is_featured=true` |
| **WB-56** | `Admin\OrderController::update()` — Update Progress | Status tidak valid | `{ status:"unknown_status" }` | 422 — "The selected status is invalid" |
| **WB-57** | `Admin\OrderController::update()` — Update Progress | Update berhasil tanpa foto | `{ status:"in_progress", progress_details:"Sedang dikerjakan", team_members:[1,2] }` | OrderDetail baru dibuat, notifikasi terkirim ke user |
| **WB-58** | `Admin\OrderController::update()` — Update Progress | Update berhasil dengan foto baru | `{ status:"completed", progress_details:"Selesai", new_photos:[photo1.jpg, photo2.jpg] }` | Foto diupload, OrderDetail baru dengan array `photos`, notifikasi terkirim |
| **WB-59** | `Admin\OrderController::update()` — Update Progress | Order tidak punya user (user null) | `$order->user` = null | OrderDetail dibuat, notifikasi TIDAK terkirim (karena `if ($order->user)`) |
| **WB-60** | `Admin\UserManagementController::index()` — Daftar User | Pencarian dengan keyword | `{ search:"john" }` | Hanya user dengan nama/email mengandung "john" yang ditampilkan |
| **WB-61** | `Admin\UserManagementController::index()` — Daftar User | Sort by kolom yang diizinkan | `{ sort:"name", direction:"asc" }` | User diurutkan berdasarkan nama ascending |
| **WB-62** | `Admin\UserManagementController::index()` — Daftar User | Sort by kolom yang TIDAK diizinkan | `{ sort:"password", direction:"asc" }` | Sort diabaikan, tetap default sort `created_at desc` |
| **WB-63** | `Admin\UserManagementController::update()` — Reset Password User | Password < 8 karakter | `{ password:"short", password_confirmation:"short" }` | 422 — "Password minimal 8 karakter" |
| **WB-64** | `Admin\UserManagementController::update()` — Reset Password User | Password valid & confirmed | `{ password:"NewPass123", password_confirmation:"NewPass123" }` | Password di-hash & diupdate, redirect ke index dengan pesan sukses |
| **WB-65** | `Admin\TeamMemberController::store()` — Tambah Anggota Tim | Posisi tidak valid (tidak ada di daftar) | `{ name:"John", position:"CEO" }` | 422 — "The selected position is invalid" |
| **WB-66** | `Admin\TeamMemberController::store()` — Tambah Anggota Tim | Data valid dengan foto | `{ name:"John", position:"Head of Design", photo:valid.jpg }` | Anggota tim tersimpan, foto diupload ke `team-photos/` |
| **WB-67** | `Admin\TeamMemberController::store()` — Tambah Anggota Tim | Data valid tanpa foto | `{ name:"John", position:"Head of Design" }` | Anggota tim tersimpan tanpa `photo_path` |
| **WB-68** | `Admin\TeamMemberController::update()` — Edit Anggota Tim | Upload foto baru, foto lama ada → dihapus | `{ name:"John", position:"Head of Design", photo:new.jpg }` (punya foto lama) | Foto lama dihapus, foto baru diupload |
| **WB-69** | `Admin\TeamMemberController::update()` — Edit Anggota Tim | Remove foto (checkbox) | `{ name:"John", position:"Head of Design", remove_photo:true }` | `photo_path` di-set null, file dihapus dari storage |
| **WB-70** | `Admin\TeamMemberController::destroy()` — Hapus Anggota Tim | Anggota tim memiliki foto | TeamMember dengan `photo_path` | Foto dihapus dari storage, anggota tim dihapus |
| **WB-71** | `Admin\TeamMemberController::destroy()` — Hapus Anggota Tim | Anggota tim tanpa foto | TeamMember tanpa `photo_path` | Anggota tim dihapus, tidak ada penghapusan file |
