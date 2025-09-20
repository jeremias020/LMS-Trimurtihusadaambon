# 🔍 Panduan Debugging Logout LMS Trimurti

## ❗ Masalah Yang Dialami
Setelah menekan tombol "Keluar" di dashboard guru, pengguna tetap berada di dashboard dan tidak diarahkan ke halaman login.

## 🛠️ Solusi Yang Telah Diterapkan

### 1. **Enhanced Logout Controller**
- ✅ Menambahkan logging detail untuk proses logout
- ✅ Menggunakan `flush()` untuk membersihkan semua session data
- ✅ Redirect langsung ke route `login` instead of `/`
- ✅ Exception handling untuk error yang mungkin terjadi

### 2. **Improved JavaScript Handler**
- ✅ Debugging console logs untuk troubleshooting
- ✅ Fallback menggunakan Fetch API jika form submit gagal
- ✅ Backup redirect timer sebagai safety net
- ✅ Error handling yang komprehensif

### 3. **Test Routes (Development Only)**
Route-route test tersedia di:
- `GET /test/session-info` - Info session dan autentikasi
- `GET /test/auth-status` - Status autentikasi saat ini
- `POST /test/force-logout` - Force logout untuk debugging

### 4. **Enhanced Route Configuration**
- ✅ Route logout dengan middleware web eksplisit
- ✅ Test routes hanya tersedia di development mode

## 🧪 Cara Testing

### **Metode 1: Browser Console Testing**

1. **Login ke Dashboard Guru**
2. **Buka Developer Tools (F12)**
3. **Masuk ke Console Tab**
4. **Jalankan command berikut:**

```javascript
// Test 1: Cek elemen logout
testLogout()

// Test 2: Cek status autentikasi
fetch('/test/auth-status')
  .then(r => r.json())
  .then(data => console.log('Auth Status:', data))

// Test 3: Cek info session
fetch('/test/session-info')
  .then(r => r.json())
  .then(data => console.log('Session Info:', data))
```

### **Metode 2: Network Tab Testing**

1. **Buka Developer Tools (F12)**
2. **Masuk ke Network Tab**
3. **Klik tombol "Keluar"**
4. **Perhatikan request yang dibuat:**
   - ✅ Harus ada POST request ke `/logout`
   - ✅ Response status 302 (redirect)
   - ✅ Location header mengarah ke `/login`

### **Metode 3: Direct URL Testing**

Akses URL berikut untuk debugging:
- `http://localhost:8000/test/auth-status`
- `http://localhost:8000/test/session-info`

### **Metode 4: Manual Force Logout**

```javascript
// Paste ini di console untuk force logout
fetch('/test/force-logout', {
  method: 'POST',
  headers: {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
  }
})
.then(r => r.json())
.then(data => {
  console.log(data);
  if(data.status === 'success') {
    window.location.href = data.redirectUrl;
  }
})
```

## 📋 Checklist Debugging

### **Frontend Issues:**
- [ ] Form `logout-form` dengan ID tersebut ada di DOM
- [ ] Link logout memiliki onclick handler yang benar
- [ ] CSRF token tersedia dan tidak expired
- [ ] JavaScript berjalan tanpa error
- [ ] Confirmation dialog muncul saat klik logout

### **Backend Issues:**
- [ ] POST request ke `/logout` berhasil terkirim
- [ ] Response status 302 dengan redirect header
- [ ] Session berhasil di-invalidate
- [ ] User berhasil di-logout dari Auth::user()

### **Browser Issues:**
- [ ] Cookies tidak diblokir oleh browser
- [ ] JavaScript enabled
- [ ] Cache tidak interfere dengan session
- [ ] Multiple tabs tidak menyebabkan session conflict

## 🔧 Troubleshooting Steps

### **Jika Logout Button Tidak Respond:**
1. Cek console untuk JavaScript errors
2. Verify form dan link elements ada di DOM
3. Test dengan `testLogout()` function

### **Jika POST Request Tidak Terkirim:**
1. Cek CSRF token validity
2. Verify route `/logout` exists dengan `php artisan route:list`
3. Check network connectivity

### **Jika Request Terkirim Tapi Tidak Redirect:**
1. Cek Laravel logs di `storage/logs/`
2. Verify session driver dan konfigurasi
3. Test dengan force logout

### **Jika Session Tidak Cleared:**
1. Cek session configuration di `.env`
2. Test dengan different session driver (file vs cookie)
3. Clear session storage manually

## 🚨 Emergency Solutions

### **Solution 1: Manual Redirect**
Tambahkan ini di console jika logout tidak bekerja:
```javascript
// Force clear dan redirect
document.cookie.split(";").forEach(function(c) { 
  document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/"); 
});
window.location.href = '/login';
```

### **Solution 2: Server-Side Force Logout**
Akses URL: `http://localhost:8000/test/force-logout` (POST request)

### **Solution 3: Clear Browser Data**
1. Clear browser cache dan cookies
2. Restart browser
3. Login ulang

## 📞 Next Steps

1. **Jalankan test methods di atas**
2. **Catat hasil dari setiap test**
3. **Share hasilnya untuk analisis lebih lanjut**

## 📁 File Yang Dimodifikasi

- `app/Http/Controllers/Auth/LoginController.php` - Enhanced logout method
- `resources/views/partials/header-guru.blade.php` - Improved JavaScript
- `routes/web.php` - Fixed route configuration
- `routes/test.php` - Test routes untuk debugging

## 🎯 Expected Results

Setelah perbaikan ini:
- ✅ Klik "Keluar" → Confirmation dialog muncul
- ✅ Klik "OK" → Loading spinner + POST ke /logout
- ✅ Server response → 302 redirect ke /login
- ✅ Browser redirect → Halaman login dengan pesan sukses
- ✅ Auth::user() → null (user sudah logout)

---

**💡 Tip:** Gunakan kombinasi console debugging + network monitoring untuk troubleshooting yang paling efektif!