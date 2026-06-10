<?php
require_once __DIR__ . '/vendor/autoload.php';

use SeoSitemap\JsonLd\SmartBuilder;

// 1. Inisialisasi SmartBuilder dengan Data Website/Organisasi Anda
$jsonLdBuilder = new SmartBuilder(
    'Toko Mantap',
    'https://tokomantap.com',
    'https://tokomantap.com/logo.png'
);

// 2. Contoh Generate JSON-LD untuk Artikel Blog
$articleJson = $jsonLdBuilder->buildForArticle([
    'url' => 'https://tokomantap.com/blog/cara-memilih-sepatu',
    'title' => 'Cara Memilih Sepatu yang Tepat',
    'description' => 'Panduan lengkap cara memilih sepatu yang nyaman untuk lari dan gaya.',
    'image' => 'https://tokomantap.com/images/sepatu-blog.jpg',
    'datePublished' => '2023-10-27T10:00:00+00:00',
    'authorName' => 'Budi Susanto',
    'breadcrumbs' => [
        ['name' => 'Home', 'url' => 'https://tokomantap.com'],
        ['name' => 'Blog', 'url' => 'https://tokomantap.com/blog'],
        ['name' => 'Cara Memilih Sepatu yang Tepat']
    ]
]);

echo "=== JSON-LD UNTUK ARTIKEL ===\n";
echo $articleJson . "\n\n";

// 3. Contoh Generate JSON-LD untuk Produk Toko Online
$productJson = $jsonLdBuilder->buildForProduct([
    'url' => 'https://tokomantap.com/produk/sepatu-lari-x1',
    'name' => 'Sepatu Lari X1 Super Cepat',
    'description' => 'Sepatu lari terbaik dengan bantalan empuk dan desain aerodinamis.',
    'image' => 'https://tokomantap.com/images/sepatu-x1.jpg',
    'sku' => 'SPT-X1-001',
    'brand' => 'MantapShoes',
    'price' => 550000,
    'priceCurrency' => 'IDR',
    'availability' => 'https://schema.org/InStock',
    'breadcrumbs' => [
        ['name' => 'Home', 'url' => 'https://tokomantap.com'],
        ['name' => 'Produk', 'url' => 'https://tokomantap.com/produk'],
        ['name' => 'Sepatu Lari X1 Super Cepat']
    ]
]);

echo "=== JSON-LD UNTUK PRODUK ===\n";
echo $productJson . "\n\n";
