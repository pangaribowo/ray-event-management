# 🚀 Vercel Environment Variables Setup Guide

## 📋 **Required Environment Variables**

Copy dan paste satu per satu ke Vercel Dashboard → Settings → Environment Variables:

### **1. Application Environment**
```
Name: APP_ENV
Value: production
```

```
Name: APP_DEBUG  
Value: false
```

### **2. Database Configuration (Supabase)**
```
Name: DB_HOST
Value: db.xxxxxxxxxxxxxx.supabase.co
```

```
Name: DB_PORT
Value: 5432
```

```
Name: DB_NAME
Value: postgres
```

```
Name: DB_USER
Value: postgres
```

```
Name: DB_PASSWORD
Value: [your_supabase_password]
```

### **3. Supabase API Credentials**
```
Name: SUPABASE_URL
Value: https://xxxxxxxxxxxxxx.supabase.co
```

```
Name: SUPABASE_ANON_KEY
Value: [your_anon_key_from_supabase]
```

```
Name: SUPABASE_SERVICE_ROLE_KEY
Value: [your_service_role_key_from_supabase]
```

### **4. Performance Settings (Optional)**
```
Name: MAX_UPLOAD_SIZE
Value: 100M
```

```
Name: MEMORY_LIMIT
Value: 256M
```

```
Name: MAX_EXECUTION_TIME
Value: 300
```

## 🔍 **Cara Mendapatkan Supabase Credentials:**

### **Database Connection Info:**
1. Login ke Supabase Dashboard
2. Pilih project Anda
3. Klik **Settings** → **Database**
4. Scroll ke **Connection parameters**
5. Copy:
   - **Host**: `db.xxxxxxxxxxxxxx.supabase.co`
   - **Port**: `5432`
   - **Database**: `postgres`
   - **Username**: `postgres`
   - **Password**: [yang Anda set saat create project]

### **API Keys:**
1. Di Supabase Dashboard
2. Klik **Settings** → **API**
3. Copy:
   - **Project URL**: `https://xxxxxxxxxxxxxx.supabase.co`
   - **anon public key**: `eyJ...` (panjang)
   - **service_role key**: `eyJ...` (panjang)

## ✅ **Verifikasi Setup:**

Setelah semua environment variables di-set:

1. **Redeploy** aplikasi di Vercel
2. **Check function logs** untuk error
3. **Test database connection** melalui aplikasi
4. **Verify** semua fitur berfungsi

## 🚨 **Important Notes:**

- ⚠️ **Jangan commit** environment variables ke Git
- 🔒 **Keep credentials secure** - hanya di Vercel dashboard
- 🔄 **Redeploy** setelah menambah environment variables
- 📊 **Monitor** function usage untuk free tier limits

## 🎯 **Auto-Detection Logic:**

Sistem akan otomatis:
- ✅ Detect `APP_ENV=production` → Use PostgreSQL
- ✅ Connect ke Supabase dengan credentials yang di-set
- ✅ Enable production mode (error logging off)
- ✅ Use optimized settings untuk performance

**Tidak perlu konfigurasi tambahan!** Sistem sudah pintar memilih database berdasarkan environment.
