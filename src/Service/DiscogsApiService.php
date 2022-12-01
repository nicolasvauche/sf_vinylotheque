<?php

namespace App\Service;

use Discogs\DiscogsClient;

class DiscogsApiService
{
    private DiscogsClient $discogsClient;

    public function __construct(DiscogsClient $discogsClient)
    {
        $this->discogsClient = $discogsClient;
    }

    public function search(string $artistName, string $albumName)
    {
        $allResults = [];
        if ($artistName && $albumName) {
            $req = [
                'release_title' => $albumName,
                'artist' => $artistName,
            ];
        } else if ($artistName) {
            $req = [
                'artist' => $artistName,
            ];
        } else {
            $req = [
                'release_title' => $albumName,
            ];
        }

        $req['format_exact'] = 'Vinyl';
        $req['per_page'] = 100;
        $req['page'] = 1;

        $results = $this->discogsClient->search($req);

        foreach ($results['results'] as $result) {
            if ($result['cover_image'] && in_array('Vinyl', $result['format']) && in_array('LP', $result['format']) && ($result['type'] === 'master' || $result['type'] === 'release')) {
                $result['artist'] = $artistName;
                $result['cover'] = $result['cover_image'];
                unset($result['cover_image']);
                $allResults[] = $result;
            }
        }

        return $allResults;
    }

    public function searchMaster(int $id)
    {
        $results = $this->discogsClient->getMaster(['id' => $id])->toArray();
        if ($results) {
            $result = [
                'discogsId' => $results['id'],
                'artist' => [
                    'discogsId' => $results['artists']['0']['id'],
                    'name' => $results['artists']['0']['name'],
                    'cover' => $results['artists'][0]['thumbnail_url'] ?? null,
                ],
                'title' => $results['title'],
                'year' => $results['year'],
                'cover' => $results['images'][0]['resource_url'] ?? null,
            ];
        }

        return $result ?? null;
    }

    public function searchRelease(int $id)
    {
        $results = $this->discogsClient->getRelease(['id' => $id])->toArray();
        if ($results) {
            $result = [
                'discogsId' => $results['id'],
                'artist' => [
                    'discogsId' => $results['artists'][0]['id'],
                    'name' => $results['artists'][0]['name'],
                    'cover' => $results['artists'][0]['thumbnail_url'] ?? null,
                ],
                'title' => $results['title'],
                'year' => $results['year'],
                'cover' => $results['images'][0]['resource_url'],
            ];
        }

        return $result ?? null;
    }
}
