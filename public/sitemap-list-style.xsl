<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9">
    <xsl:output method="html" encoding="UTF-8" indent="yes"/>

    <xsl:template match="/">
        <html>
        <head>
            <title>XML Sitemap Feed - Index</title>
            <style>
                body { font-family: Arial, sans-serif; font-size: 14px; }
                table { border-collapse: collapse; width: 100%; }
                th, td { border: 1px solid #ccc; padding: 8px; text-align: left;font-size: 12px; }
                th { background-color: #f0f0f0; }
                a { color: #000; }
            </style>
        </head>
        <body>
        <h1>XML Sitemap Feed - Index</h1>
        <div style="display: flex; flex-direction: column; gap: 10px;padding: 10px;color: #666;">
        <small>Bu XML Sitemap Index haritası Google, Bing, Yahoo! ve Ask gibi arama motorlarının sitenizin daha hızlı index alması için sevdiği bir haritadır. Bu klavuz haritasını Google Webmaster Tools ya da Bing Webmaster Tools hesabınızdaki site haritaları sekmesinden index haritası olarak gönderin.</small>
        </div>

        <table>
            <tr>
                <th>#</th>
                <th>XML Sitemap</th>
                <th>Last Changed</th>
            </tr>
            <xsl:for-each select="//sitemap:url">
                <tr>
                    <td><xsl:value-of select="position()"/></td>
                    <td>
                        <a href="{sitemap:loc}" target="_blank">
                            <xsl:value-of select="sitemap:loc"/>
                        </a>
                    </td>
                    <td><xsl:value-of select="sitemap:lastmod"/></td>
                </tr>
            </xsl:for-each>
        </table>
        </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
