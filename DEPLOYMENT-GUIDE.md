# 🚀 Panduan Deployment Event Management System
## Supabase Database + Vercel Hosting

### 📋 **Prasyarat**
- Akun Supabase (gratis)
- Akun Vercel (gratis)
- Akun GitHub (untuk deployment)
- Git terinstall di komputer

---

## 🗄️ **LANGKAH 1: Setup Database Supabase**

### 1.1 Buat Project Supabase
1. Kunjungi [supabase.com](https://supabase.com)
2. Klik **"Start your project"** → **"New project"**
3. Pilih organisasi atau buat baru
4. Isi project details:
   - **Name**: `event-management-db`
   - **Database Password**: Buat password kuat (simpan baik-baik!)
   - **Region**: Pilih terdekat (Singapore untuk Indonesia)
5. Klik **"Create new project"**
6. Tunggu ~2 menit hingga database siap

### 1.2 Jalankan Migration SQL
1. Di dashboard Supabase, klik **"SQL Editor"** di sidebar
2. Klik **"New query"**
3. Copy-paste seluruh isi file `supabase-migration.sql`
4. Klik **"Run"** untuk mengeksekusi
5. Pastikan semua table berhasil dibuat (lihat di **"Table Editor"**)

### 1.3 Catat Credentials Database
1. Klik **"Settings"** → **"Database"**
2. Scroll ke **"Connection parameters"**
3. Catat informasi berikut:
   ```
   Host: db.xxxxxxxxxxxxxx.supabase.co
   Port: 5432
   Database: postgres
   Username: postgres
   Password: [password yang Anda buat]
   ```

### 1.4 Dapatkan API Keys
1. Klik **"Settings"** → **"API"**
2. Catat:
   - **Project URL**: `https://xxxxxxxxxxxxxx.supabase.co`
   - **anon public key**: `eyJ...`
   - **service_role key**: `eyJ...`

---

## ☁️ **LANGKAH 2: Deploy ke Vercel**

### 2.1 Persiapan Repository
```bash
# Di terminal, masuk ke folder project
cd /home/bakung/Downloads/projek-event

# Initialize git repository
git init
git add .
git commit -m "Initial commit: Event Management System"

# Push ke GitHub (buat repository baru di GitHub dulu)
git remote add origin https://github.com/USERNAME/event-management-system.git
git branch -M main
git push -u origin main
```

### 2.2 Deploy di Vercel
1. Kunjungi [vercel.com](https://vercel.com)
2. Login dengan GitHub
3. Klik **"New Project"**
4. Import repository GitHub yang baru dibuat
5. **Framework Preset**: Pilih **"Other"**
6. **Root Directory**: Biarkan kosong (default)
7. Klik **"Deploy"**

### 2.3 Setting Environment Variables di Vercel
1. Setelah deploy selesai, klik **"Settings"**
2. Klik **"Environment Variables"**
3. Tambahkan variable berikut satu per satu:

   ```
   SUPABASE_URL = https://xxxxxxxxxxxxxx.supabase.co
   SUPABASE_ANON_KEY = eyJ... (anon key dari Supabase)
   SUPABASE_SERVICE_ROLE_KEY = eyJ... (service role key dari Supabase)
   DB_HOST = db.xxxxxxxxxxxxxx.supabase.co
   DB_PORT = 5432
   DB_NAME = postgres
   DB_USER = postgres
   DB_PASSWORD = [password database Anda]
   APP_ENV = production
   APP_DEBUG = false
   ```

4. Setelah semua ditambahkan, klik **"Redeploy"** di tab **"Deployments"**

---

## 🌐 **LANGKAH 3: Setup Domain (Opsional)**

### 3.1 Domain Gratis (Vercel Subdomain)
- Aplikasi Anda otomatis tersedia di: `https://event-management-system.vercel.app`
- Atau domain vercel lainnya seperti: `https://event-management-system-git-main-username.vercel.app`

### 3.2 Custom Domain
1. Di dashboard Vercel project, klik **"Settings"** → **"Domains"**
2. Masukkan domain yang Anda miliki (contoh: `eventmanagement.yourdomain.com`)
3. Ikuti instruksi untuk setup DNS:
   - **Type**: CNAME
   - **Name**: eventmanagement (atau subdomain lain)
   - **Value**: cname.vercel-dns.com
4. Tunggu propagasi DNS (~24 jam maksimal)

---

## 🔧 **LANGKAH 4: Testing & Verifikasi**

### 4.1 Test Akses Aplikasi
1. Buka URL aplikasi Anda
2. Anda akan melihat halaman login
3. Gunakan kredensial default:
   - **Username**: admin@yourdomain.com
   - **Password**: password

### 4.2 Test Fungsi Database
1. Login ke aplikasi
2. Coba buat user baru
3. Coba buat business block
4. Coba buat event booking
5. Pastikan semua data tersimpan dengan benar

### 4.3 Monitor Logs
1. Di Vercel dashboard, klik **"Functions"**
2. Klik pada function untuk melihat logs
3. Check error logs jika ada masalah

---

## 📊 **LANGKAH 5: Monitoring & Maintenance**

### 5.1 Supabase Monitoring
- **Database**: Monitor usage di Supabase dashboard
- **Queries**: Lihat slow queries di **"SQL Editor"**
- **Storage**: Monitor storage usage (free tier: 500MB)

### 5.2 Vercel Monitoring
- **Functions**: Monitor function invocations
- **Bandwidth**: Monitor bandwidth usage (free tier: 100GB/month)
- **Build Time**: Monitor build performance

### 5.3 Security Best Practices
1. **Ganti password default admin** setelah first login
2. **Enable 2FA** di Supabase dan Vercel accounts
3. **Regularly backup database** via Supabase dashboard
4. **Monitor access logs** untuk aktivitas mencurigakan

---

## 🔍 **Troubleshooting**

### Database Connection Error
```bash
# Check environment variables
echo $DB_HOST
echo $DB_USER

# Test connection from local
php -r "
try {
    \$pdo = new PDO('pgsql:host=DB_HOST;port=5432;dbname=postgres', 'postgres', 'PASSWORD');
    echo 'Connection successful!';
} catch (Exception \$e) {
    echo 'Error: ' . \$e->getMessage();
}
"
```

### Vercel Deployment Issues
1. Check build logs di **"Deployments"** tab
2. Ensure `vercel.json` is properly configured
3. Check PHP version compatibility
4. Verify environment variables are set

### Common Issues & Solutions

**Issue**: "Function timeout"
**Solution**: Optimize database queries, add indexes

**Issue**: "Memory limit exceeded"
**Solution**: Optimize code, reduce memory usage

**Issue**: "SSL connection error"
**Solution**: Add `sslmode=require` to PostgreSQL connection string

---

## 🎯 **Next Steps & Optimization**

### Performance Optimization
1. **Add caching**: Implement Redis caching for frequently accessed data
2. **Optimize images**: Use Vercel Image Optimization
3. **Database indexing**: Add indexes for commonly queried columns
4. **Code minification**: Minify CSS/JS assets

### Security Enhancements
1. **Implement CSRF protection**
2. **Add rate limiting**
3. **Use prepared statements** for all queries
4. **Regular security audits**

### Feature Enhancements
1. **Email notifications**: Integrate with SendGrid/Mailgun
2. **File uploads**: Use Supabase Storage for file management
3. **Real-time updates**: Implement with Supabase Realtime
4. **Mobile responsiveness**: Enhance mobile UI/UX

---

## 📞 **Support & Resources**

- **Supabase Docs**: https://supabase.com/docs
- **Vercel Docs**: https://vercel.com/docs
- **PHP on Vercel**: https://vercel.com/docs/runtimes/php
- **PostgreSQL Docs**: https://www.postgresql.org/docs/

**Catatan Penting**: 
- Free tier Supabase: 500MB storage, 2GB bandwidth/month
- Free tier Vercel: 100GB bandwidth, 6000 function invocations/month
- Monitor usage untuk menghindari overage charges

---

**🎉 Selamat! Aplikasi Event Management System Anda sudah live di production!**
