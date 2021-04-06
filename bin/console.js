/**
 * Copyright (c) 2021 Strategio Digital s.r.o.
 * @author Jiří Zapletal (https://strategio.digital, jz@strategio.digital)
 */

const Apify = require('apify');
const ElasticManager = require('./../app/node/ElasticManager');

Apify.main(async () => {
    // Create a RequestList
    const requestList = await Apify.openRequestList('start-urls', [
        { url: 'https://foxentry.cz/funkce/', method: 'GET' },
    ]);

    const handlePageFunction = async ({ $ }) => {

        // Extract data from raw-html
        const crawlResult = $('.functions-all__item').map((i, element) => {
            return {
                title: $(element).find('h4').text(),
                description: $(element).find('p').text()
            }
        }).get();

        // Create index if not exists
        await ElasticManager.createIndex();

        // Save crawl result
        await ElasticManager.bulkInsert(crawlResult);
    };

    const crawler = new Apify.CheerioCrawler({
        requestList,
        handlePageFunction,
    });

    await crawler.run();
});