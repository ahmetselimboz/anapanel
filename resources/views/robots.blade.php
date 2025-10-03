# Site Haritaları
Sitemap: {{route('sitemapList')}}
Sitemap: {{asset('sitemap-articles.xml')}}
Sitemap: {{route('sitemapNews')}}
Sitemap: {{route('sitemapgoogle')}}

User-agent: *
Disallow: /public
Disallow: /public/*
Disallow: /tr/*
Disallow: /*?ref=
Disallow: /*?q=
Disallow: /admin/
Disallow: /admin/
Disallow: /uploads/

Disallow: /*?*
Disallow: /*.php$
Allow: /

User-agent: AdsBot-Google
Disallow: /advert/*
Allow: /

User-agent: SemrushBot
Crawl-delay: 2

User-agent: AhrefsBot
Crawl-Delay: 2

User-agent: Googlebot-News
Allow: /genel/
