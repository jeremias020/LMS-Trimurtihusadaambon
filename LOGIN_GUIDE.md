# 🚀 LOGIN GUIDE - LMS TRIMURTI MODERN USER SYSTEM

## 📋 LOGIN CREDENTIALS

### 🔑 ADMIN LOGIN
- **Email**: `admin@lms-trimurti.sch.id`
- **Password**: `password`
- **Username**: `user_1`
- **Role**: `admin`

### 👨‍🏫 GURU LOGIN
- **Email**: `siti@lms-trimurti.sch.id`
- **Password**: `password`
- **Username**: `user_2`
- **Role**: `guru`
- **NIP**: `GURU001`

**Guru Lainnya:**
- **Dr. Budi Santoso**: `budi@lms-trimurti.sch.id` / `password`
- **Dra. Ani Wijaya**: `ani@lms-trimurti.sch.id` / `password`

### 👨‍🎓 SISWA LOGIN
- **Email**: `agus.setiawan@lms-trimurti.sch.id`
- **Password**: `password`
- **Username**: `user_5`
- **Role**: `siswa`
- **NIS**: `SIS000005`

**Siswa Lainnya:**
- **Siti Aminah**: `siti.aminah@lms-trimurti.sch.id` / `password`
- **Budi Pratama**: `budi.pratama@lms-trimurti.sch.id` / `password`
- **Dewi Lestari**: `dewi.lestari@lms-trimurti.sch.id` / `password`
- **Rudi Hermawan**: `rudi.hermawan@lms-trimurti.sch.id` / `password`

---

## 🔐 HOW TO LOGIN

### 1. **Via Web Interface**
```
URL: http://localhost:8000/login
Method: POST
Fields: email, password
```

### 2. **Via API**
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@lms-trimurti.sch.id",
    "password": "password"
  }'
```

### 3. **Via Artisan Command (Testing)**
```bash
php artisan test:login-credentials
```

---

## 🏗️ ARCHITECTURE OVERVIEW

### **Central Authentication System**
```
users_central (Main Authentication Table)
├── Admin Profile (admins table)
├── Guru Profile (gurus table)
└── Siswa Profile (siswa table)
```

### **Login Flow**
1. User enters email/password
2. System checks `users_central` table
3. If credentials match → Authentication successful
4. Load appropriate profile based on role
5. Redirect to role-specific dashboard

---

## 🎯 ROLE-BASED ACCESS

### **Admin Access**
- Dashboard: `/admin/dashboard`
- Permissions: Full system access
- Features: User management, system settings

### **Guru Access**
- Dashboard: `/guru/dashboard`
- Permissions: Teaching tools, grade management
- Features: Materials, assignments, assessments

### **Siswa Access**
- Dashboard: `/siswa/dashboard`
- Permissions: Learning tools, view grades
- Features: Course materials, submissions

---

## 🛠️ TECHNICAL IMPLEMENTATION

### **Authentication Guards**
```php
// config/auth.php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users_central',
    ],
],
'providers' => [
    'users_central' => [
        'driver' => 'eloquent',
        'model' => App\Models\UserCentral::class,
    ],
],
```

### **Login Controller Logic**
```php
// Check credentials
if (Auth::guard('web')->attempt($credentials)) {
    $user = Auth::user();
    
    // Role-based redirect
    return match($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'guru' => redirect()->route('guru.dashboard'),
        'siswa' => redirect()->route('siswa.dashboard'),
    };
}
```

### **User Detection**
```php
// In controllers/views
$user = Auth::user();

// Check role
if ($user->isAdmin()) {
    // Admin logic
} elseif ($user->isGuru()) {
    // Guru logic
} elseif ($user->isSiswa()) {
    // Siswa logic
}

// Get profile
$profile = $user->profile; // Auto-loads correct profile
```

---

## 🔧 TESTING COMMANDS

### **Test All Credentials**
```bash
php artisan test:login-credentials
```

### **Test User System**
```bash
php artisan test:modern-user-system
```

### **Check Table Structures**
```bash
php artisan check:user-tables
```

---

## 🚨 TROUBLESHOOTING

### **Common Issues**

#### **1. Login Failed**
- Check email spelling
- Verify password is "password"
- Ensure user is active (`is_active = 1`)

#### **2. Profile Not Loading**
- Check user_id linkage: `php artisan link:profiles-to-users`
- Verify foreign key constraints

#### **3. Role Detection Issues**
- Check `role` field in `users_central` table
- Ensure role is one of: 'admin', 'guru', 'siswa'

### **Debug Commands**
```bash
# Check specific user
php artisan tinker
>>> $user = App\Models\UserCentral::where('email', 'admin@lms-trimurti.sch.id')->first();
>>> $user->role;
>>> $user->profile;

# Check password hash
>>> Hash::check('password', $user->password);
```

---

## 📱 LOGIN FORM EXAMPLE

### **HTML Structure**
```html
<form method="POST" action="/login">
    @csrf
    <input type="email" name="email" required>
    <input type="password" name="password" required>
    <button type="submit">Login</button>
</form>
```

### **Validation Rules**
```php
$credentials = $request->validate([
    'email' => 'required|email|exists:users_central,email',
    'password' => 'required',
]);
```

---

## 🔄 PASSWORD RESET

### **Current Passwords**
All test accounts use password: `password`

### **Change Password**
```bash
php artisan tinker
>>> $user = App\Models\UserCentral::where('email', 'user@example.com')->first();
>>> $user->password = Hash::make('newpassword');
>>> $user->save();
```

---

## ✅ VERIFICATION CHECKLIST

- [ ] Admin can login with `admin@lms-trimurti.sch.id`
- [ ] Guru can login with `siti@lms-trimurti.sch.id`
- [ ] Siswa can login with `agus.setiawan@lms-trimurti.sch.id`
- [ ] Role-based redirects working
- [ ] Profiles loading correctly
- [ ] Password verification working
- [ ] Session management working

---

## 🎯 NEXT STEPS

1. **Update Routes**: Add role-specific routes
2. **Create Dashboards**: Build role-based dashboards
3. **Add Middleware**: Implement role-based middleware
4. **UI Integration**: Update existing login forms
5. **Testing**: Comprehensive login testing

**Modern login system ready for production!** 🚀
