<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Elastic\Elasticsearch\ClientBuilder;

class EsCreateIndexesCommand extends Command
{
    protected $signature = 'es:create-indexes';
    protected $description = 'Create Elasticsearch indexes and mappings';

    public function handle()
    {
        $hosts = config('elasticsearch.hosts');
        $client = ClientBuilder::create()->setHosts($hosts)->build();

        $index = 'products_index';

        // read mapping file
        $mappingPath = resource_path('elasticsearch/mappings/products.json');
        $mapping = json_decode(file_get_contents($mappingPath), true);

        // delete if exists (dev)
        if ($client->indices()->exists(['index' => $index])->asBool()) {
            $this->info("Deleting existing index: $index");
            $client->indices()->delete(['index' => $index]);
        }

        $this->info("Creating index: $index");
        $client->indices()->create([
            'index' => $index,
            'body' => $mapping
        ]);

        $this->info('Index created.');
    }
}
