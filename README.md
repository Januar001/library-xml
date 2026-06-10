# SEO Sitemap Generator

Sebuah library PHP profesional berbasis Object Oriented (OOP) untuk menghasilkan XML Sitemap terstruktur dengan standar hierarki dan gaya visual persis seperti plugin Yoast SEO WordPress. 

Library ini mendukung:
- Sitemap Index (`sitemap_index.xml`)
- Sub-sitemap individual (seperti `post-sitemap.xml`)
- Dukungan Sitemap Gambar (`<image:image>`)
- Built-in XSLT Stylesheet yang membuat sitemap Anda tampil sebagai tabel rapi ketika diakses di browser.
- **[BARU]** Smart JSON-LD Generator (Article, Product, WebPage, Organization).

## Instalasi

Gunakan [Composer](https://getcomposer.org/) untuk menginstal library ini ke dalam project PHP Anda:

```bash
composer require januar001/xml-seo-sitemap
```

### Setup Routing Sitemap (Opsional tapi Direkomendasikan)
Agar URL `/sitemap.xml` di website Anda otomatis diarahkan ke `/sitemap_index.xml` (persis seperti cara kerja Yoast SEO), jalankan perintah berikut setelah menginstal library:

```bash
php vendor/bin/seo-setup
```
Perintah di atas akan secara aman menambahkan *RewriteRule* ke dalam file `.htaccess` Anda.

**Cara Manual (Jika tidak menggunakan perintah di atas):**
Jika Anda tidak ingin menjalankan script otomatis, Anda bisa menyalin kode berikut dan menempelkannya secara manual ke dalam file `.htaccess` di *root* direktori web Anda:

```apache
# Routing Sitemap (Mirip Yoast SEO) oleh januar001/xml-seo-sitemap
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteRule ^sitemap\.xml$ sitemap_index.xml [L]
</IfModule>
```

## Cara Menggunakan

Berikut adalah langkah demi langkah cara menggunakan library ini untuk membuat sitemap lengkap beserta styling-nya.

### 1. Inisialisasi Generator
Pertama, kita siapkan *Generator* dan pastikan file stylesheet (XSL) tercopy ke folder publik Anda agar sitemap Anda tampil rapi.

```php
require_once __DIR__ . '/vendor/autoload.php';

use SeoSitemap\SitemapIndex;
use SeoSitemap\Sitemap;
use SeoSitemap\Tags\Url;
use SeoSitemap\Tags\Image;
use SeoSitemap\Generator;

// Tentukan direktori tempat sitemap akan disimpan (misal folder public html)
$publicDir = __DIR__ . '/public';

// Copy XSL stylesheet bawaan ke folder publik Anda
copy(__DIR__ . '/assets/main-sitemap.xsl', $publicDir . '/main-sitemap.xsl');

// Inisialisasi Generator dan beri tahu nama file stylesheet-nya
$generator = new Generator('main-sitemap.xsl');
```

### 2. Membuat Sub-Sitemap (Misal: Posts / Artikel)
Sitemap jenis ini berisi kumpulan URL ke halaman atau artikel di website Anda, beserta informasi gambar di dalamnya.

```php
$postSitemap = new Sitemap();

// Menambah URL biasa
$postSitemap->addUrl(
    new Url('https://example.com/cara-membuat-sitemap', '2023-10-26T09:00:00+00:00')
);

// Menambah URL yang menyertakan Gambar (Image Sitemap)
$postSitemap->addUrl(
    (new Url('https://example.com/artikel-bergambar', '2023-10-27T10:00:00+00:00', 'weekly', '0.8'))
        ->addImage(new Image('https://example.com/gambar-1.jpg', 'Judul Gambar 1'))
        ->addImage(new Image('https://example.com/gambar-2.jpg', 'Judul Gambar 2', 'Caption Gambar 2'))
);

// Generate XML-nya dan simpan sebagai file
$postXml = $generator->generateSitemap($postSitemap);
file_put_contents($publicDir . '/post-sitemap.xml', $postXml);
```

### 3. Membuat Sitemap Index Utama
*Sitemap Index* adalah file utama yang Anda daftarkan ke Google Search Console. File ini berisi daftar *link* ke sub-sitemap Anda yang lainnya.

```php
$sitemapIndex = new SitemapIndex();

// Masukkan link menuju sub-sitemap yang sudah kita generate sebelumnya
$sitemapIndex->addSitemap('https://example.com/post-sitemap.xml', '2023-10-27T10:00:00+00:00');
$sitemapIndex->addSitemap('https://example.com/page-sitemap.xml', '2023-01-02T12:00:00+00:00');

// Generate XML-nya dan simpan sebagai file index
$indexXml = $generator->generateIndex($sitemapIndex);
file_put_contents($publicDir . '/sitemap_index.xml', $indexXml);
```

### 4. Membuat Smart JSON-LD (Schema Markup)
Library ini memiliki "Otak Pintar" untuk merakit JSON-LD ala Yoast SEO (menggabungkan *Organization*, *WebPage*, dan konten secara otomatis). Sangat bagus untuk SEO Google Rich Snippet.

```php
use SeoSitemap\JsonLd\SmartBuilder;

// A. Inisialisasi Organisasi / Web Anda
$jsonLdBuilder = new SmartBuilder(
    'Toko Mantap',
    'https://tokomantap.com',
    'https://tokomantap.com/logo.png'
);

// B. Generate JSON-LD untuk Halaman Artikel Blog
$articleJson = $jsonLdBuilder->buildForArticle([
    'url' => 'https://tokomantap.com/blog/sepatu',
    'title' => 'Cara Memilih Sepatu',
    'description' => 'Panduan lengkap cara memilih sepatu.',
    'image' => 'https://tokomantap.com/images/sepatu.jpg',
    'datePublished' => '2023-10-27T10:00:00+00:00',
    'authorName' => 'Budi Susanto',
    'breadcrumbs' => [
        ['name' => 'Home', 'url' => 'https://tokomantap.com'],
        ['name' => 'Blog', 'url' => 'https://tokomantap.com/blog'],
        ['name' => 'Cara Memilih Sepatu']
    ]
]);

// C. Generate JSON-LD untuk Halaman Produk (Toko Online)
$productJson = $jsonLdBuilder->buildForProduct([
    'url' => 'https://tokomantap.com/produk/sepatu-x1',
    'name' => 'Sepatu Lari X1',
    'description' => 'Sepatu lari terbaik.',
    'image' => 'https://tokomantap.com/images/x1.jpg',
    'sku' => 'SPT-X1-001',
    'brand' => 'MantapShoes',
    'price' => 550000,
    'priceCurrency' => 'IDR',
    'breadcrumbs' => [
        ['name' => 'Home', 'url' => 'https://tokomantap.com'],
        ['name' => 'Produk', 'url' => 'https://tokomantap.com/produk'],
        ['name' => 'Sepatu Lari X1']
    ]
]);

// D. Generate JSON-LD untuk Bisnis Lokal (Local Business)
$localBusinessJson = $jsonLdBuilder->buildForLocalBusiness([
    'url' => 'https://tokomantap.com/kontak',
    'title' => 'Toko Mantap Jakarta',
    'description' => 'Kunjungi toko offline kami di Jakarta.',
    'telephone' => '+6281234567890',
    'address' => [
        'streetAddress' => 'Jl. Sudirman No. 1',
        'addressLocality' => 'Jakarta',
        'postalCode' => '12190',
        'addressCountry' => 'ID'
    ]
]);

// E. Generate JSON-LD untuk Halaman FAQ (Tanya Jawab)
$faqJson = $jsonLdBuilder->buildForFAQPage([
    'url' => 'https://tokomantap.com/faq',
    'title' => 'Pertanyaan Seputar Toko Mantap',
    'questionsAndAnswers' => [
        [
            'question' => 'Apakah barang original?',
            'answer' => 'Ya, semua barang 100% original.'
        ],
        [
            'question' => 'Bisa bayar di tempat (COD)?',
            'answer' => 'Bisa, kami mendukung layanan COD ke seluruh Indonesia.'
        ]
    ]
]);

// F. Generate JSON-LD untuk Resep Makanan
$recipeJson = $jsonLdBuilder->buildForRecipe([
    'url' => 'https://tokomantap.com/resep/nasi-goreng',
    'title' => 'Resep Nasi Goreng Spesial',
    'description' => 'Cara membuat nasi goreng mantap dengan mudah.',
    'image' => 'https://tokomantap.com/images/nasgor.jpg',
    'authorName' => 'Chef Budi',
    'prepTime' => 'PT15M',
    'cookTime' => 'PT10M',
    'recipeYield' => '2 porsi',
    'recipeIngredient' => ['2 piring nasi putih', '3 siung bawang merah', '1 butir telur'],
    'recipeInstructions' => ['Panaskan minyak', 'Masukkan telur', 'Masukkan nasi dan bumbu']
]);

// G. Generate JSON-LD untuk Acara (Event)
$eventJson = $jsonLdBuilder->buildForEvent([
    'url' => 'https://tokomantap.com/event/sale-akhir-tahun',
    'title' => 'Mega Sale Akhir Tahun',
    'description' => 'Diskon besar-besaran hingga 90%!',
    'startDate' => '2023-12-25T08:00:00+07:00',
    'endDate' => '2023-12-31T23:59:00+07:00',
    'location' => [
        'name' => 'Toko Mantap Pusat',
        'address' => ['streetAddress' => 'Jl. Sudirman No. 1', 'addressLocality' => 'Jakarta', 'addressCountry' => 'ID']
    ]
]);

// Terakhir, Anda tinggal meng-echo salah satu string JSON yang dihasilkan ke dalam tag <script> di bagian <head> HTML Anda:
// echo '<script type="application/ld+json">' . $articleJson . '</script>';
```

## Referensi Class

### `Url`
Digunakan untuk merepresentasikan tag `<url>` dalam sitemap.
- `__construct(string $loc, ?string $lastmod = null, ?string $changefreq = null, ?string $priority = null)`
- `addImage(Image $image): self`

### `Image`
Digunakan untuk merepresentasikan tag `<image:image>` di dalam sebuah URL.
- `__construct(string $loc, ?string $title = null, ?string $caption = null)`

### `Sitemap`
Digunakan untuk menyimpan sekumpulan objek `Url` (menjadi `<urlset>`).
- `addUrl(Url $url): self`

### `SitemapIndex`
Digunakan untuk menyimpan referensi ke sitemap-sitemap lain (menjadi `<sitemapindex>`).
- `addSitemap(string $loc, ?string $lastmod = null): self`

### `Generator`
Kelas utama yang memformat objek-objek di atas menjadi *string* XML murni dengan DOMDocument, sehingga memastikan validitas tag dan struktur XML.
- `generateIndex(SitemapIndex $index): string`
- `generateSitemap(Sitemap $sitemap): string`
