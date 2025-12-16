<?php

namespace App\Http\Controllers;

use App\Services\GithubService;

class GithubController extends Controller
{
    protected $git;

    public function __construct(GithubService $git)
    {
        $this->git = $git;
    }

    public function createRepo($name)
    {
        return $this->git->createRepo($name);
    }

    public function listRepos()
    {
        return $this->git->listRepos();
    }

    public function deleteRepo($repo)
    {
        return response()->json([
            'status' => $this->git->deleteRepo($repo) == 204 ? 'Deleted ✅' : 'Failed ❌'
        ]);
    }

    public function createReadme($repo)
    {
        $content = "# Auto Repo\nCreated using Laravel 🚀";
        return $this->git->createFile($repo, 'README.md', $content);
    }

    public function fork($owner, $repo)
    {
        return $this->git->fork($owner, $repo);
    }

    public function createBranch($repo, $branch)
    {
        return $this->git->createBranch($repo, $branch);
    }

    public function pullRequest($repo)
    {
        return $this->git->createPR($repo, 'New PR from Laravel', 'dev');
    }

    public function commitPush($repo)
    {
        $files = [
            "README.md" => "# Auto Commit\n\nDone using Laravel 🚀",
            "src/demo.txt" => "This file was committed automatically",
            "index.html" => "<h1>Hello from Laravel Commit Automation</h1>"
        ];

        return $this->git->commitAndPush($repo, $files, "Laravel – auto commit push");
    }
}
