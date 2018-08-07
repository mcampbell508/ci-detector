<?php declare(strict_types=1);

namespace OndraM\CiDetector\Ci;

use OndraM\CiDetector\CiDetector;
use OndraM\CiDetector\Env;

class GitLab extends AbstractCi
{
    public static function isDetected(Env $env): bool
    {
        return $env->get('GITLAB_CI') !== false;
    }

    public function getCiName(): string
    {
        return CiDetector::CI_GITLAB;
    }

    public function getBuildNumber(): string
    {
        return $this->env->getString('CI_BUILD_ID');
    }

    public function getBuildUrl(): string
    {
        return $this->env->getString('CI_PROJECT_URL') . '/builds/' . $this->getBuildNumber();
    }

    public function getGitCommit(): string
    {
        $serverVersion = $this->getServerVersion();

        $env = (version_compare($serverVersion, '9.0.0') >= 0) ? "CI_COMMIT_SHA" : "CI_BUILD_REF";

        return $this->env->getString($env);
    }

    public function getGitBranch(): string
    {
        $serverVersion = $this->getServerVersion();

        $env = (version_compare($serverVersion, '9.0.0') >= 0) ? "CI_COMMIT_REF_NAME" : "CI_BUILD_REF_NAME";

        return $this->env->getString($env);
    }

    public function getRepositoryUrl(): string
    {
        $serverVersion = $this->getServerVersion();

        $env = (version_compare($serverVersion, '9.0.0') >= 0) ? "CI_REPOSITORY_URL" : "CI_BUILD_REPO";

        return $this->env->getString($env);
    }

    public function getServerVersion(): string
    {
        return str_replace(["-ee", "-ce"], '', $this->env->getString("CI_SERVER_VERSION"));
    }
}
