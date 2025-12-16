<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GithubService
{
    protected $token;
    protected $username;

    public function __construct()
    {
        $this->token    = env('GITHUB_TOKEN');
        $this->username = env('GITHUB_USERNAME');
    }

    private function client()
    {
        return Http::withHeaders([
            'Authorization' => "Bearer {$this->token}",
            'Accept'        => 'application/vnd.github+json'
        ]);
    }

    /* ✅ Create Repository */
    public function createRepo($name)
    {
        return $this->client()
            ->post('https://api.github.com/user/repos', [
                'name'        => $name,
                'private'     => false,
                'auto_init'   => true,
                'description' => 'Created by Laravel automation'
            ])
            ->json();
    }

    /* ✅ List Repositories */
    public function listRepos()
    {
        return $this->client()
            ->get('https://api.github.com/user/repos')
            ->json();
    }

    /* ✅ Delete Repository */
    public function deleteRepo($repo)
    {
        return $this->client()
            ->delete("https://api.github.com/repos/{$this->username}/{$repo}")
            ->status();
    }

    /* ✅ Create / Update File */
    public function createFile($repo, $path, $content)
    {
        $url = "https://api.github.com/repos/{$this->username}/{$repo}/contents/{$path}";

        return $this->client()
            ->put($url, [
                "message" => "Auto commit from Laravel",
                "content" => base64_encode($content)
            ])
            ->json();
    }

    /* ✅ Fork Repository */
    public function fork($owner, $repo)
    {
        return $this->client()
            ->post("https://api.github.com/repos/{$owner}/{$repo}/forks")
            ->json();
    }

    /* ✅ Create Branch */
    public function createBranch($repo, $newBranch, $base = 'main')
    {
        // Get SHA of base branch
        $baseBranch = $this->client()
            ->get("https://api.github.com/repos/{$this->username}/{$repo}/git/ref/heads/{$base}")
            ->json();

        $sha = $baseBranch['object']['sha'];

        return $this->client()
            ->post("https://api.github.com/repos/{$this->username}/{$repo}/git/refs", [
                "ref" => "refs/heads/{$newBranch}",
                "sha" => $sha
            ])
            ->json();
    }

    /* ✅ Create Pull Request */
    public function createPR($repo, $title, $head, $base = 'main')
    {
        return $this->client()
            ->post("https://api.github.com/repos/{$this->username}/{$repo}/pulls", [
                "title" => $title,
                "head"  => $head,
                "base"  => $base,
                "body"  => "Auto PR from Laravel"
            ])
            ->json();
    }

    /* -----------------------------------------------------------
    ✅ ADD - COMMIT - PUSH AUTOMATION
    ----------------------------------------------------------- */

    public function commitAndPush($repo, $files, $message = "Auto commit from Laravel", $branch = 'main')
    {
        $base = "https://api.github.com/repos/{$this->username}/{$repo}";

        // 1. Get latest commit SHA of branch
        $branchData = $this->client()
            ->get("{$base}/git/ref/heads/{$branch}")
            ->json();

        $latestCommitSha = $branchData['object']['sha'];

        // 2. Get base tree SHA
        $commitData = $this->client()
            ->get("{$base}/git/commits/{$latestCommitSha}")
            ->json();

        $baseTree = $commitData['tree']['sha'];

        // 3. Create new tree
        $tree = [];

        foreach ($files as $path => $content) {
            $tree[] = [
                "path"    => $path,
                "mode"    => "100644",
                "type"    => "blob",
                "content" => $content
            ];
        }

        $newTree = $this->client()
            ->post("{$base}/git/trees", [
                "base_tree" => $baseTree,
                "tree"      => $tree
            ])
            ->json();

        // 4. Create commit
        $commit = $this->client()
            ->post("{$base}/git/commits", [
                "message" => $message,
                "tree"    => $newTree['sha'],
                "parents" => [$latestCommitSha]
            ])
            ->json();

        // 5. Update branch (Push)
        $this->client()
            ->patch("{$base}/git/refs/heads/{$branch}", [
                "sha"   => $commit['sha'],
                "force" => true
            ]);

        return [
            'status' => '✅ Commit & Push Successful',
            'commit' => $commit['sha']
        ];
    }

}
