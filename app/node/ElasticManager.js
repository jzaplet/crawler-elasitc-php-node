/**
 * Copyright (c) 2021 Strategio Digital s.r.o.
 * @author Jiří Zapletal (https://strategio.digital, jz@strategio.digital)
 */

require('dotenv').config();
const { Client } = require('@elastic/elasticsearch');
const INDEX_NAME = 'functions_via_nodejs';

const client = new Client({
    node: `${process.env.ELASTIC_SCHEME}://${process.env.ELASTIC_HOST}:${process.env.ELASTIC_PORT}`,
    auth: {
        username: process.env.ELASTIC_USER,
        password: process.env.ELASTIC_PASS
    }
})

const ElasticManger = {
    /**
     * Bulk insert
     * @param crawlResults
     * @returns {Promise<void>}
     */
    bulkInsert: async (crawlResults) => {
        const body = crawlResults.flatMap(doc => [{index: { _index: INDEX_NAME }}, doc]);
        await client.bulk({ body })
    },

    /**
     * Create index if not exists
     * @returns {Promise<void>}
     */
    createIndex: async () => {
        const params = { index: INDEX_NAME }
        if (!await client.indices.exists(params)) {
            await client.indices.create(params);
        }
    }
}

module.exports = ElasticManger;