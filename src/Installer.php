<?php
namespace SeoSitemap;

class Installer
{
    public static function setupHtaccess(\Composer\Script\Event $event)
    {
        $io = $event->getIO();
        
        // Dapatkan direktori utama tempat composer dijalankan (contoh: root project aplikasi web pengguna)
        $rootDir = getcwd();
        $htaccessPath = $rootDir . DIRECTORY_SEPARATOR . '.htaccess';

        $ruleToAdd = "\n# Routing Sitemap (Mirip Yoast SEO) oleh januar001/xml-seo-sitemap\n<IfModule mod_rewrite.c>\nRewriteEngine On\nRewriteRule ^sitemap\.xml$ sitemap_index.xml [L]\n</IfModule>\n";

        // Cek apakah file .htaccess ada
        if (file_exists($htaccessPath)) {
            $content = file_get_contents($htaccessPath);
            
            // Cek apakah rules sudah pernah ditambahkan sebelumnya
            if (strpos($content, 'RewriteRule ^sitemap\.xml$ sitemap_index.xml [L]') === false) {
                // Tambahkan rule di bagian paling akhir
                file_put_contents($htaccessPath, $ruleToAdd, FILE_APPEND);
                $io->write("<info>xml-seo-sitemap:</info> Sukses menambahkan RewriteRule sitemap ke .htaccess!");
            } else {
                $io->write("<info>xml-seo-sitemap:</info> RewriteRule sitemap sudah ada di .htaccess. Melewati penambahan.");
            }
        } else {
            // Jika .htaccess tidak ada, buat baru
            file_put_contents($htaccessPath, $ruleToAdd);
            $io->write("<info>xml-seo-sitemap:</info> Membuat file .htaccess baru dengan RewriteRule sitemap.");
        }
    }
}
