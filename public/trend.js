import puppeteer from "puppeteer";

async function getGoogleTrends() {
    const browser = await puppeteer.launch({ headless: true });
    const page = await browser.newPage();

    await page.goto('https://trends.google.com/trends/trendingsearches/daily?geo=TR', { waitUntil: 'networkidle2' });

    const trends = await page.evaluate(() => {
        return Array.from(document.querySelectorAll('tbody > tr')).map(el => {
            const parts = el.innerText.split('\n').filter(part => part.trim() !== '');
            return {
                title: parts[0],
                searchVolume: parts[1],
                increase: parts[2],
                time: parts[3],
                status: parts[4]
            };
        });
    });

    await browser.close();
    return JSON.stringify(trends);
}

getGoogleTrends().then(console.log).catch(console.error);
