# Physical Data Model & Activity Diagram — Fitur Pemesanan Kendaraan

Dokumen ini berisi rancangan Physical Data Model (ERD) dan Activity Diagram untuk fitur pemesanan kendaraan pada aplikasi monitoring kendaraan tambang nikel. Skenario yang digunakan: pemesanan diinput oleh admin, ditugaskan ke driver, dan harus disetujui secara berjenjang minimal 2 level sebelum kendaraan bisa dipakai.

## 1. Physical Data Model (ERD)

Model ini fokus ke tabel-tabel yang berhubungan langsung dengan fitur pemesanan kendaraan (`bookings`, `booking_approvals`), ditambah tabel pendukung: user & pegawai, lokasi/region (kantor pusat, kantor cabang, 6 tambang), kendaraan (milik sendiri maupun sewa), driver, riwayat pemakaian, konsumsi BBM, jadwal service, dan log aplikasi.

```mermaid
erDiagram
    locations ||--o{ employees : "menempatkan"
    locations ||--o{ vehicles : "pool_di"
    locations ||--o{ drivers : "berbasis_di"

    employees ||--o| users : "memiliki_akun"
    employees ||--o{ bookings : "mengajukan"

    users ||--o{ bookings : "menginput (admin)"
    users ||--o{ booking_approvals : "menyetujui (approver)"
    users ||--o{ application_logs : "melakukan_aksi"

    vehicle_categories ||--o{ vehicles : "mengkategorikan"
    rental_companies ||--o{ vehicles : "menyewakan"

    vehicles ||--o{ bookings : "dipesan_pada"
    vehicles ||--o{ fuel_logs : "konsumsi_bbm"
    vehicles ||--o{ service_schedules : "dijadwalkan_service"

    drivers ||--o{ bookings : "ditugaskan_pada"

    bookings ||--o{ booking_approvals : "memiliki_persetujuan"
    bookings ||--o| vehicle_usage_history : "menghasilkan_riwayat"
    bookings ||--o{ fuel_logs : "terkait_pengisian"

    locations {
        int id PK
        varchar_100 name
        enum type "kantor_pusat|kantor_cabang|tambang"
        varchar_255 address
    }

    employees {
        int id PK
        varchar_20 nip
        varchar_100 name
        varchar_50 position
        varchar_50 department
        int location_id FK
        varchar_20 phone
        varchar_100 email
    }

    users {
        int id PK
        varchar_50 username
        varchar_255 password_hash
        enum role "admin|approver"
        int employee_id FK
        boolean is_active
        datetime created_at
    }

    vehicle_categories {
        int id PK
        varchar_50 name "angkutan_orang|angkutan_barang"
        varchar_255 description
    }

    rental_companies {
        int id PK
        varchar_100 name
        varchar_50 contact_person
        varchar_20 phone
        varchar_255 address
    }

    vehicles {
        int id PK
        varchar_15 plate_number
        varchar_50 brand
        varchar_50 model
        int year
        int category_id FK
        enum ownership_type "milik_sendiri|sewa"
        int rental_company_id FK
        int location_id FK
        enum status "tersedia|digunakan|maintenance"
        int capacity
        varchar_20 fuel_type
    }

    drivers {
        int id PK
        varchar_100 name
        varchar_30 license_number
        date license_expiry
        varchar_20 phone
        int location_id FK
        enum status "available|on_duty|off"
    }

    bookings {
        int id PK
        varchar_30 booking_code
        int requester_id FK
        int vehicle_id FK
        int driver_id FK
        varchar_255 purpose
        varchar_255 destination
        datetime start_datetime
        datetime end_datetime
        int passenger_or_load_qty
        enum status "menunggu_persetujuan|disetujui|ditolak|selesai|dibatalkan"
        int created_by FK
        datetime created_at
        datetime updated_at
    }

    booking_approvals {
        int id PK
        int booking_id FK
        tinyint approval_level "1,2,..."
        int approver_id FK
        enum status "menunggu|disetujui|ditolak"
        varchar_255 notes
        datetime approved_at
    }

    vehicle_usage_history {
        int id PK
        int booking_id FK
        int start_odometer
        int end_odometer
        datetime actual_start_time
        datetime actual_end_time
        varchar_255 notes
    }

    fuel_logs {
        int id PK
        int vehicle_id FK
        int booking_id FK
        date fuel_date
        decimal_10_2 liters
        decimal_12_2 cost
        int odometer
    }

    service_schedules {
        int id PK
        int vehicle_id FK
        varchar_100 service_type
        date scheduled_date
        date completed_date
        decimal_12_2 cost
        varchar_255 notes
        enum status "terjadwal|selesai|terlewat"
    }

    application_logs {
        int id PK
        int user_id FK
        varchar_100 activity
        varchar_50 module
        varchar_50 reference_table
        int reference_id
        varchar_45 ip_address
        datetime created_at
    }
```

### Catatan Desain

- `booking_approvals` dipisah dari `bookings` supaya approval berjenjang minimal 2 level bisa didukung, dan bisa ditambah level ke-3 dst tanpa ubah struktur tabel (dibedakan lewat kolom `approval_level`).
- `vehicle_usage_history` mencatat data aktual (odometer, waktu riil) setelah kendaraan benar-benar dipakai, terpisah dari rencana pemesanan di `bookings`.
- `fuel_logs` dan `service_schedules` jadi sumber data untuk dashboard grafik pemakaian kendaraan dan laporan periodik.
- `application_logs` memenuhi syarat "log aplikasi pada tiap proses" — dicatat setiap ada aksi (input pemesanan, approve, reject, dsb).
- `users.employee_id` bisa NULL kalau ada admin yang murni akun sistem (bukan pegawai operasional).

## 2. Activity Diagram — Fitur Pemesanan Kendaraan

Alur berikut mengikuti ketentuan soal: admin menginput pemesanan, lalu disetujui secara berjenjang (2 level) sebelum kendaraan bisa digunakan.

```mermaid
flowchart TD
    Start([Mulai]) --> A1[Admin login ke aplikasi]
    A1 --> A2[Admin input data pemesanan:<br/>kendaraan, driver, tujuan,<br/>waktu, approver level 1 & 2]
    A2 --> A3[Sistem menyimpan data pemesanan<br/>status: Menunggu Persetujuan Level 1]
    A3 --> A4[Sistem catat log & kirim notifikasi<br/>ke Approver Level 1]

    A4 --> B1[Approver Level 1 login<br/>& membuka daftar pemesanan]
    B1 --> B2{Approver Level 1<br/>menyetujui?}
    B2 -- Tidak --> B3[Status: Ditolak Level 1]
    B3 --> B4[Sistem catat log & notifikasi<br/>ke Admin & Pemohon]
    B4 --> End1([Selesai - Ditolak])

    B2 -- Ya --> C1[Status: Menunggu Persetujuan Level 2]
    C1 --> C2[Sistem catat log & kirim notifikasi<br/>ke Approver Level 2]
    C2 --> C3[Approver Level 2 login<br/>& membuka daftar pemesanan]
    C3 --> C4{Approver Level 2<br/>menyetujui?}

    C4 -- Tidak --> D1[Status: Ditolak Level 2]
    D1 --> D2[Sistem catat log & notifikasi<br/>ke Admin & Pemohon]
    D2 --> End2([Selesai - Ditolak])

    C4 -- Ya --> E1[Status: Disetujui]
    E1 --> E2[Sistem catat log & notifikasi<br/>ke Admin, Driver & Pemohon]
    E2 --> E3[Kendaraan & driver siap digunakan<br/>sesuai jadwal pemesanan]
    E3 --> F1[Setelah pemakaian, Admin/Driver<br/>input data riwayat pemakaian<br/>odometer, waktu aktual, BBM]
    F1 --> F2[Status: Selesai]
    F2 --> End3([Selesai - Kendaraan Terpakai])
```

### Penjelasan Alur

1. Admin menginput pemesanan sekaligus menentukan driver dan approver di tiap level (sesuai poin b pada soal).
2. Persetujuan berjalan berjenjang — level 2 baru aktif setelah level 1 approve (poin c).
3. Approver melakukan persetujuan langsung melalui aplikasi, dengan opsi approve/reject beserta catatan (poin d).
4. Jika ditolak di level manapun, proses berhenti dan status serta notifikasi dikirim ke admin/pemohon.
5. Setiap perubahan status (input, approve, reject, selesai) dicatat ke `application_logs` untuk audit trail (poin instruksi c).
6. Setelah disetujui penuh dan kendaraan selesai dipakai, data riwayat pemakaian dicatat — data ini jadi sumber dashboard grafik pemakaian kendaraan dan laporan periodik (poin e & f).
