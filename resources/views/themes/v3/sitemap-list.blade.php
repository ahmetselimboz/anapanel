
@php
echo   "<?xml version='1.0' encoding='UTF-8'?>
<?xml-stylesheet type=\"text/xsl\" href=\"sitemap-list-style.xsl\"?>
  ";
@endphp
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

@foreach ($xmlFiles as $file)
        <url>
            <loc>{{ $file['loc'] }}</loc>
            <lastmod>{{ date('Y-m-d', strtotime($file['lastmod'])) }} </lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.9</priority>
        </url>
    @endforeach
</urlset>
