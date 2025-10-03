<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9">
    <xsl:output method="html" encoding="UTF-8" indent="yes"/>

    <xsl:template match="/">
        <html>
        <head>
            <title>Google News Sitemap Feed</title>
            <style>
                body { font-family: Arial, sans-serif; font-size: 14px; }
                table { border-collapse: collapse; width: 100%; }
                th, td { border: 1px solid #ccc; padding: 8px; text-align: left;font-size: 12px; }
                th { background-color: #f0f0f0; }
                a { color: #000; }
            </style>
        </head>
        <body>
        <h1>Google News Sitemap Feed</h1>
        <div style="display: flex; flex-direction: column; gap: 10px;padding: 10px;color: #666;">
        <small>Bu döküman Google News'te web sitenizdeki haberlerinizin yayınlanması için kullanılan Google News Sitemap bir rota haritasıdır.</small>
        <small>Lütfen unutmayın! Google News sadece ve sadece son 48 saat içindeki gönderilerinizi dikkate alacaktır.</small>
        <small>This sitemap contains xx URLs.</small>
        </div>

        <table>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Language</th>
                <th>Genres</th>
                <th>Keywords</th>
                <th>Published Dateage</th>
            </tr>
            <xsl:for-each select="//sitemap:url">
                <tr>
                    <td><xsl:value-of select="position()"/></td>
                    <td>
                        <a href="{sitemap:loc}" target="_blank">
                            <xsl:value-of select="sitemap:title"/>
                        </a>
                    </td>
                    <td><xsl:value-of select="sitemap:language"/></td>
                    <td><xsl:value-of select="sitemap:genres"/></td>
                    <td><xsl:value-of select="sitemap:keywords"/></td>
                    <td><xsl:value-of select="sitemap:publishedDate"/></td>
                </tr>
            </xsl:for-each>
        </table>
        </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
