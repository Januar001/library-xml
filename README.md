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

*(Catatan: Anda mungkin harus menyesuaikan perintah di atas setelah library ini dipublikasikan ke Packagist)*

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
    'authorName' => 'Budi Susanto'
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
    'priceCurrency' => 'IDR'
]);

// Anda tinggal meng-echo string JSON tersebut ke dalam tag <script> di HTML:
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
