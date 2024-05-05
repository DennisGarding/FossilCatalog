<?php

namespace App\Update;

class Tag
{
    private string $name;
    private string $zipBallUrl;
    private string $sha;

    /**
     * @param array{name: string, zipball_url: string, commit: array{sha: string}} $tagData
     */
    public function __construct(array $tagData)
    {
        $this->name = $tagData['name'];
        $this->zipBallUrl = $tagData['zipball_url'];
        $this->sha = $tagData['commit']['sha'];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getZipBallUrl(): string
    {
        return $this->zipBallUrl;
    }

    public function getSha(): string
    {
        return $this->sha;
    }
}
