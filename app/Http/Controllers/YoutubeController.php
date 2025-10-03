<?php

namespace App\Http\Controllers;


use Exception;
use Google\Service\YouTube;
use Google\Client;
use Request;


class YoutubeController extends Controller
{
    public function getClient()
    {
        try {

            $client = new Client();
            $token = session('google_access_token');
            $client->setAccessToken($token);
            return new YouTube($client);
        } catch (Exception $e) {
            return $e;
        }
    }


    public function getUserVideo()
    {
        try {
            $limit = request()->query("limit", 20);

            $youtube = $this->getClient();

            // "Uploads" playlist ID'sini al
            $response = $youtube->channels->listChannels('contentDetails', [
                'mine' => true
            ]);

            $uploadsPlaylistId = $response->getItems()[0]->getContentDetails()->getRelatedPlaylists()->getUploads();

            // Playlist'teki videoları listele
            $videos = $youtube->playlistItems->listPlaylistItems('snippet', [
                'playlistId' => $uploadsPlaylistId,
                'maxResults' => $limit
            ]);


            $list = [];
            foreach ($videos->getItems() as $video) {
                //dump($video->getSnippet()->getThumbnails());
                $videoId = $video->getSnippet()->getResourceId()->getVideoId();
                $list[] = [
                    "title" => $video->getSnippet()->getTitle(),
                    "description" => $video->getSnippet()->getDescription(),
                    "publishedAt" => $video->getSnippet()->getPublishedAt(),
                    "image" => optional($video->getSnippet()->getThumbnails()->getHigh())->getUrl(),
                    "embedUrl" => "https://www.youtube.com/embed/" . $videoId
                ];

            }

            return response()->json($list);

        } catch (Exception $e) {
            return response()->json($e);

        }
    }

    public function getVideoByTitle()
    {
        try {
            $youtube = $this->getClient();

            $query = request()->query("title", null);


            if ($query === null) {
                return response()->json([]);
            }

            $limit = request()->query("limit", 20);

            $searchResponse = $youtube->search->listSearch('snippet', [
                'q' => $query,
                'maxResults' => $limit,
                'type' => 'video', // Sadece videoları getir
             
            ]);

            $list = [];

            foreach ($searchResponse->getItems() as $video) {
                $videoId = $video->getId()->getVideoId();
                $embedUrl = "https://www.youtube.com/embed/" . $videoId;

                $list[] = [
                    'title' => $video->getSnippet()->getTitle(),
                    "description" => $video->getSnippet()->getDescription(),
                    "publishedAt" => $video->getSnippet()->getPublishedAt(),
                    "image" => optional($video->getSnippet()->getThumbnails()->getHigh())->getUrl(),
                    'embedUrl' => $embedUrl
                ];
            }

            return response()->json($list);

        } catch (Exception $e) {
            // dump($e);
            return response()->json(["error" => $e->getMessage()], 500);

        }
    }
}