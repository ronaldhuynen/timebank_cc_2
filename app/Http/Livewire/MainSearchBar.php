<?php

namespace App\Http\Livewire;

use Elastic\Elasticsearch\Client;
use Livewire\Component;
use Matchish\ScoutElasticSearch\MixedSearch;
use ONGR\ElasticsearchDSL\Highlight\Highlight;
use ONGR\ElasticsearchDSL\Query\Compound\BoolQuery;
use ONGR\ElasticsearchDSL\Query\FullText\MatchQuery;
use ONGR\ElasticsearchDSL\Query\FullText\MultiMatchQuery;
use ONGR\ElasticsearchDSL\Query\TermLevel\FuzzyQuery;
use ONGR\ElasticsearchDSL\Search;

class MainSearchBar extends Component
{
    public $search;
    public $results = [];


    public function render()
    {
        return view('livewire.main-search-bar');
    }

    public function updatedSearch()
    {
        $search = $this->search;

        if (strlen($search) > 1) {     // Because we use the fuzzy search character '~', we need to check if $searchQuery is > 1
            // Remove special characters that conflict with Elesticsearch query from $search
            $search = preg_replace('/[^a-zA-Z0-9\s]/', '', $search);
            // Add fuzzy search character '~' to $search
            $search = $search . '~';
        }

        $rawOutput = MixedSearch::search($search, function (Client $client, Search $body) use ($search) {
            $body->setSource(['id', '__class_name', '_score']);

            $fields = [
                'translations.title',
                'translations.excerpt',
                'translations.content',
                'category.names',
                'name',
                'about',
                'locations.district',
                'locations.city',
                'locations.division',
                'locations.country',
                'tags.contexts.tags.name',
                'tags.contexts.categories.translations.name',
            ];

            $boostedFields = [
                'name' => 2,
                'tags.contexts.categories.translations.name' => 1.2,
                'tags.contexts.tags.name' => 1.5,
                // Add more fields here...
            ];

            $boolQuery = new BoolQuery();

            // Construct fields parameter with boosts
            $fieldsWithBoosts = [];
            foreach ($fields as $field) {
                $fieldsWithBoosts[] = isset($boostedFields[$field]) ? $field . '^' . $boostedFields[$field] : $field;
            }

            $multiMatchQuery = new MultiMatchQuery($fieldsWithBoosts, $search, [
                'fuzziness' => config('timebank-cc.main_search_bar.fuzziness'),
                'prefix_length' => 1, //characters at the beginning of the word that must match
                'max_expansions' => 10, //maximum number of terms to which the query will expand
            ]);

            $boolQuery->add($multiMatchQuery, BoolQuery::SHOULD);
            $body->addQuery($boolQuery);

            $highlight = new Highlight();
            foreach ($fields as $field) {
                $highlight->addField($field, [
                    'fragment_size' => 70,
                    'fragmenter' => 'simple',
                    'number_of_fragments' => 10,
                    'pre_tags' => ['<span class="font-extrabold italic">'],
                    'post_tags' => ['</span>'],
                ]);
            }
            $body->addHighlight($highlight);


            $body->setSize(100);    // get max results

            return $client->search(['index' => [
                'posts_index',
                'users_index',
                'organizations_index',
                ], 'body' => $body->toArray()])->asArray();
        })->raw();

        $results = $rawOutput['hits']['hits'];
        info($results);

        $extractedData = array_map(function ($result) {
            $score = $result['_score'];
            if ($result['_source']['__class_name'] === 'App\Models\Post') {
                $score *= 1.5; // Apply multiplier to the score if the model is a Post
            }
            return [
                'id' => $result['_source']['id'],
                'model' => $result['_source']['__class_name'],
                'score' => $score,
                'highlight' => $result['highlight'][array_key_first($result['highlight'])] ?? null,
            ];
        }, $results);

        // Sort the extracted data by score in descending order
        $extractedData = collect($extractedData)->sortByDesc('score')->all();
        //$this->emit('resultsUpdated', $extractedData);


        $this->results = $extractedData;
    }
}
