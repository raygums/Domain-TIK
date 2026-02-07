┌─────────────┐
│ User Isi    │
│ Form        │──┐
└─────────────┘  │
                 ▼
        ┌───────────────┐
        │ RegisterRequest│
        │ Validation     │
        └───────┬───────┘
                ▼
        ┌───────────────┐
        │ RegisterController
        │ ::store()      │
        └───────┬───────┘
                ▼
        ┌───────────────┐
        │ UserService   │
        │ ::register()  │
        └───────┬───────┘
                ▼
     ┌──────────────────────┐
     │ 1. Get role "Pengguna"│
     │ 2. Upload file       │
     │ 3. Hash password     │
     │ 4. Create user       │
     │    (a_aktif=false)   │
     │ 5. Audit log         │
     └──────────┬───────────┘
                ▼
        ┌───────────────┐
        │ Redirect ke   │
        │ Login dengan  │
        │ Flash Message │
        └───────────────┘