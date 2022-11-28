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

    public function search($artistName, $albumName)
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
        $req['type'] = 'master';
        $req['format_exact'] = 'Vinyl';
        $req['per_page'] = 10;
        $req['page'] = 1;

        $results = $this->discogsClient->search($req);
        foreach ($results['results'] as $result) {
            if ($result['cover_image']) {
                $result['artist'] = $artistName;
                $result['cover'] = $result['cover_image'];
                unset($result['cover_image']);
                $allResults[] = $result;
            }
        }

        return $allResults;
    }
}
