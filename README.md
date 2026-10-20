# Trijaya Firewall — WordPress Theme v1.4.2

**Theme:** Trijaya Firewall
**Versi:** 1.4.2
**Dibuat oleh:** [Tim Trijaya Solution](https://trijayasolution.com)
**Kompatibilitas:** WordPress 6.0+ (tested 7.1) · PHP 8.0+ (disarankan 8.2)

Theme modern & kekinian untuk website bisnis network security, cyber security,
dan firewall. Font header **Sora**, body **Inter**, warna **light**, dan
**full responsive**.

---

## ✨ Fitur Utama

| Fitur | Keterangan |
|---|---|
| 🎨 Desain modern | Light theme, gradient, glassmorphism header, animasi halus |
| 🔤 Font | Sora (heading) + Inter (body) via Google Fonts |
| 🖼️ Hero animasi + slider | 1 slide = hero statis beranimasi. **Isi lebih dari 1 slide = otomatis jadi slider** (autoplay, dots, panah, swipe, progress bar) |
| 🤖 AI Chat (Groq) | Widget chat floating terhubung ke Groq API (bisa diganti ke xAI Grok / OpenAI-compatible lain). API key aman di server via REST proxy |
| 🧩 Elementor ready | Header/footer/section bisa dioverride Elementor & Elementor Pro Theme Builder. Setiap section homepage juga bisa diganti dengan halaman Elementor ber-slug sama |
| 📋 Homepage SEO | Struktur section mengikuti Workflow.txt: Firewall, Network Security, Cyber Security, Network Infrastructure, Solutions/Brand, Services, Portfolio, Insight, FAQ, Kontak |
| 🧭 Menu lengkap 3 level | Struktur menu sesuai dokumen *Menu Website Trijayafirewall.com*: **HOME \| SOLUTIONS \| SERVICES \| PRODUCTS \| INDUSTRIES \| PROJECTS \| INSIGHTS \| ABOUT US \| CONTACT** + CTA *Konsultasi Gratis*. Menu tampil otomatis (fallback bawaan) walau belum dibuat di Appearance → Menus, dropdown 3 level dengan flyout, accordion mobile drawer |
| 🎨 Customizable | Logo, 5 hero slide, warna aksen, info kontak, isi AI chat — semua via **Customizer** |
| ⚡ Setup 1 klik (import JSON) | Halaman admin **Trijaya Setup** meng-*import* `inc/demo-content.json` — 122 halaman berkonten SEO + 11 kategori blog + **Menu Utama (3 level) & Menu Footer** tersusun otomatis. Aman diulang, bisa di-download dari admin |
| 📱 Full responsive | Breakpoint 1240 / 1080 / 1024 / 900 / 640 / 400px, mobile drawer menu (dengan CTA di dalam drawer), **anti horizontal-scroll** (tidak ada elemen yang melewati margin viewport), tabel & embed scroll-aman, dukungan reduced-motion |
| 📲 PWA-ready | Installable ke homescreen (Android/iOS/desktop), service worker dengan mode offline, halaman offline kustom, ikon any + maskable, manifest dinamis ikut warna aksen & Site Icon |

---

## 🚀 Instalasi

1. Zip folder `trijaya-firewall` (atau pakai `trijaya-firewall.zip` bila tersedia).
2. WP Admin → **Appearance → Themes → Add New → Upload Theme** → pilih ZIP → **Install** → **Activate**.
3. Disarankan install & aktifkan plugin **Elementor** (opsional, untuk edit section).
4. Buka **Appearance → Trijaya Setup** → klik **“🚀 Import Halaman & Menu Sekarang”**.

## ⚙️ Konfigurasi

### Logo
- **Appearance → Customize → Site Identity → Logo** untuk upload logo custom.
- Tanpa logo custom, theme otomatis memakai logo bawaan (`assets/img/logo.png`).

### Hero / Slider
- **Customize → ⭐ Hero / Slider Homepage**.
- Slide 1 sudah terisi default (“Jasa Firewall & Network Security Indonesia”).
- Aktifkan & isi Slide 2 dst → homepage otomatis menjadi **slider**.

### AI Chat (Groq)
- **Customize → 🤖 AI Chat (Groq / Grok)**.
- Isi **API Key** dari [console.groq.com](https://console.groq.com) (gratis).
- Default model: `llama-3.3-70b-versatile` (bisa diganti).
- Endpoint bisa diganti ke `https://api.x.ai/v1/chat/completions` (Grok) atau provider OpenAI-compatible lain.
- Chat bekerja via REST proxy `/wp-json/trijaya/v1/chat` — API key tidak pernah terekspos di browser. Dilengkapi rate-limit 20 pesan/5 menit/IP.

### PWA (Progressive Web App)
- Aktif otomatis — tanpa plugin tambahan.
- Buka situs di Android/Chrome → menu ⋮ → **“Install app”** / banner “Tambahkan ke layar utama”. iOS Safari → Share → **“Add to Home Screen”**.
- Manifest & service worker disajikan dari root (`/?tj_pwa=manifest`, `/?tj_pwa=sw`) sehingga scope mencakup seluruh situs.
- Halaman offline otomatis (`/?tj_pwa=offline`) dengan tombol coba lagi.
- Ikon memakai **Site Icon** bila di-set (Settings → General → Site Icon), jika tidak memakai ikon bawaan theme `assets/img/icon-*.png`.
- `theme_color` manifest & `theme-color` browser otomatis mengikuti warna aksen di Customizer.
- Matikan via `add_filter('trijaya_pwa_enable', '__return_false');`.

### Edit Section dengan Elementor
1. Buat halaman dengan **slug section**, contoh: `layanan`, `solutions`, `faq`, `kontak`, `hero`, `why`, `portfolio`, `insight`, `services`, `brands`.
2. Edit halaman tersebut dengan **Elementor** dan Publish.
3. Section bawaan otomatis digantikan konten Elementor Anda.
4. Alternatif: pakai shortcode `[trijaya_section slug="hero"]` di halaman/widget Elementor mana pun.
5. Elementor Pro Theme Builder juga didukung (location `header` & `footer`).

### Struktur Menu (sesuai dokumen “Menu Website Trijayafirewall.com”)

Header: **HOME | SOLUTIONS | SERVICES | PRODUCTS | INDUSTRIES | PROJECTS | INSIGHTS | ABOUT US | CONTACT** + tombol CTA **Konsultasi Gratis** (WhatsApp).

> 💡 **Menu langsung tampil** walau belum di-setup — fallback bawaan dari `inc/menus.php`. Halaman & menu asli WordPress dibuat via **import JSON** (lihat di bawah).

- **Solutions** — Network Solution (Network Infrastructure, LAN & WAN, Wireless Network, Fiber Optic, Network Monitoring) · Cyber Security (Firewall, Endpoint Security, Network Security, VPN, Vulnerability Assessment, Security Monitoring) · Server & Data Center (Server, Storage & NAS, Virtualization, Backup, Data Center, Disaster Recovery) · Physical Security (CCTV, Access Control, Parking System, Fire Alarm, Security System) · Software & Digital (Custom Application, Website Development, Mobile Application, ERP, Odoo, System Integration)
- **Services** — IT Support, Managed IT Services, IT Support & Managed Services 24/7 (unggulan ⭐), Network/Firewall/Server/CCTV Installation, Fiber Optic Splicing, IT Maintenance, Hardware Maintenance, Software Development, Cloud & Hosting, Cyber Security Services
- **Products** — Network (Switch, Router, Access Point, Firewall, SFP & Transceiver) · Server & Storage (Server, NAS, Storage, UPS, Rack Server) · Security (CCTV, NVR, Access Control, Fire Alarm, X-Ray Security) · IT Hardware (PC, Laptop, Monitor, Printer, Accessories) · **Brand**: Fortinet, Sophos, Ruijie, Aruba, Juniper, Mikrotik, Synology, QNAP, Nutanix, Palo Alto (landing page tersendiri: `/fortinet/`, `/sophos/`, dst.)
- **Industries** — Manufacturing, Oil & Gas, Mining, Hospital & Healthcare, Education, Hospitality, Government, Banking & Finance, Logistics, Retail, Construction, Transportation
- **Projects** — Network Infrastructure, Cyber Security, Data Center, CCTV & Security, Fiber Optic, Software Development, IT Support & Maintenance
- **Insights** — kategori blog: IT Infrastructure, Network, Cyber Security, Firewall, Server & Storage, CCTV, Fiber Optic, Software, Cloud, Tips & Tutorial, News
- **About Us** — About Trijaya Solution, Vision & Mission, Why Choose Us, Our Team, Certifications, Partners, Clients & Partners, Careers
- **Contact** — Request a Quote, Technical Consultation, Service Request, Location, WhatsApp

Struktur ini didefinisikan di `inc/menus.php` (`trijaya_menu_tree()`) — dipakai sebagai **fallback menu** (theme langsung menampilkan menu lengkap walau Appearance → Menus masih kosong) dan sebagai sumber generator `inc/demo-content.json`. Bisa dikustomisasi lewat **Appearance → Menus** tanpa batas.

### 📦 Import Demo Content via JSON

File `inc/demo-content.json` berisi **paket demo lengkap**:

| Bagian | Isi |
|---|---|
| `pages` | 122 halaman berkonten SEO siap pakai (Gutenberg blocks): Solutions (5 kategori × sub-solusi), Services (13 layanan), Products (5 kategori + brand), Industries (12 industri), Projects, About Us, Contact, halaman brand (/fortinet/, /sophos/, dst.) |
| `categories` | 11 kategori blog untuk submenu Insights (IT Infrastructure, Network, Cyber Security, Firewall, dst. + deskripsi SEO) |
| `menus.primary` | Menu Utama 3 level — 133 item persis struktur dokumen Word (termasuk item unggulan ⭐ IT Support 24/7 & WhatsApp custom link) |
| `menus.footer` | Menu Footer — 9 item |

Tersedia juga **`inc/demo-content.wxr.xml`** (WXR 1.2, 123 halaman + 11 kategori + 142 item menu) untuk diimport via **Tools → Import → WordPress** — format standar WordPress Importer.
| `home`, `blog`, `settings` | Homepage statis + halaman blog Insights |

**Cara A — Import via Theme (disarankan, tanpa plugin):**
1. WP Admin → **Appearance → Trijaya Setup**.
2. Klik **“🚀 Import Halaman & Menu Sekarang”** — sekali klik, anti-timeout (WP_IMPORTING + set_time_limit + deferred counting), aman diulang bila pernah putus (melanjutkan yang tersisa).
3. Tombol **“⬇️ Download JSON”** tersedia untuk backup / tool lain.

**Cara B — Import via WordPress Importer (Tools → Import):**
1. Di halaman Trijaya Setup, klik **“⬇️ Download XML (untuk WordPress Importer)”** → file `trijaya-demo-content.xml` (WXR 1.2: 123 halaman + 11 kategori, tanggal masa lalu, tanpa menu item — ringan & anti-error).
2. Buka **Tools → Import → WordPress → Install Now / Run Importer** → upload XML → Submit.
3. Setelah selesai, buka halaman admin mana pun (mis. **Pages**) — theme **otomatis membangun Menu Utama & Menu Footer** dari `demo-content.json` dan men-set homepage + halaman blog Insights (auto-fix hanya mengisi yang kosong, tidak menimpa setelan Anda).
4. Tips bila server lambat: nonaktifkan sementara plugin berat lain saat import.

> ⚠️ **Penting:** WordPress Importer hanya menerima **.XML (WXR)** — jangan upload file `.json` ke Tools → Import (format salah dapat memicu *critical error*).

Path file bisa dioverride:
```php
add_filter( 'trijaya_demo_content_path', fn() => '/path/ke/custom.json' );
add_filter( 'trijaya_demo_wxr_path', fn() => '/path/ke/custom.xml' );
```

---

## 📁 Struktur File

```
trijaya-firewall/
├── style.css              # Theme header (v1.3.0)
├── functions.php          # Setup, assets, includes
├── front-page.php         # Homepage (section / Elementor)
├── header.php / footer.php
├── index, page, single, archive, search, 404, comments, searchform
├── inc/
│   ├── sections.php       # 10 section homepage
│   ├── customizer.php     # Hero, warna, kontak, AI chat
│   ├── chat-api.php       # REST proxy Groq
│   ├── menus.php          # Struktur menu 3 level + fallback wp_nav_menu
│   ├── demo-content.json  # Paket import: 122 halaman + kategori + menu
│   ├── demo-content.wxr.xml # WXR untuk WordPress Importer (Tools → Import)
│   ├── demo-setup.php     # Setup 1-klik (import JSON + download)
│   ├── elementor.php      # Theme locations + shortcode
│   ├── pwa.php            # Manifest, service worker, offline
│   └── template-tags.php
├── template-parts/        # header/footer default, kartu post, chat widget
└── assets/                # css, js, img (logo + ikon PWA)
```

## 📄 Lisensi
GPL v2 atau lebih baru. Footer memuat kredit “Powered by
[Trijaya Solution](https://trijayasolution.co.id)”.

---

© 2026 Trijaya Solution — [trijayasolution.com](https://trijayasolution.com)
# trijaya-firewall
