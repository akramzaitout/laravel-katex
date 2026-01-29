<?php

declare(strict_types=1);
/**
 * Automated Semantic Versioning & Release Management
 *
 * Usage: php release.php [options]
 * Options:
 *   --branch=<name>    Target branch (default: main)
 *   --dry-run          Preview changes without committing
 *   --interactive      Interactive mode with prompts
 *   --push             Automatically push to remote
 *   --help             Show this help message
 */
class ReleaseManager
{
    private string $branch;

    private string $composerFile = 'composer.json';

    private string $changelogFile = 'CHANGELOG.md';

    private bool $dryRun = false;

    private bool $interactive = false;

    private bool $autoPush = false;

    private array $composer;

    private string $currentVersion;

    private array $conventionalTypes = [
        'feat' => ['bump' => 'minor', 'label' => '✨ Features'],
        'fix' => ['bump' => 'patch', 'label' => '🐛 Bug Fixes'],
        'perf' => ['bump' => 'patch', 'label' => '⚡ Performance'],
        'refactor' => ['bump' => 'patch', 'label' => '♻️  Refactoring'],
        'docs' => ['bump' => 'patch', 'label' => '📚 Documentation'],
        'style' => ['bump' => 'patch', 'label' => '💄 Styling'],
        'test' => ['bump' => 'patch', 'label' => '✅ Tests'],
        'build' => ['bump' => 'patch', 'label' => '🔧 Build'],
        'ci' => ['bump' => 'patch', 'label' => '👷 CI/CD'],
        'chore' => ['bump' => 'patch', 'label' => '🔨 Chores'],
        'revert' => ['bump' => 'patch', 'label' => '⏪ Reverts'],
    ];

    public function __construct(array $argv)
    {
        $this->parseArguments($argv);
    }

    private function parseArguments(array $argv): void
    {
        foreach ($argv as $arg) {
            if (str_starts_with($arg, '--branch=')) {
                $this->branch = substr($arg, 9);
            } elseif ($arg === '--dry-run') {
                $this->dryRun = true;
            } elseif ($arg === '--interactive' || $arg === '-i') {
                $this->interactive = true;
            } elseif ($arg === '--push') {
                $this->autoPush = true;
            } elseif ($arg === '--help' || $arg === '-h') {
                $this->showHelp();
                exit(0);
            }
        }

        $this->branch = $this->branch ?? 'main';
    }

    private function showHelp(): void
    {
        echo <<<'HELP'
        🚀 Automated Semantic Versioning & Release Management
        
        Usage: php release.php [options]
        
        Options:
          --branch=<name>    Target branch (default: main)
          --dry-run          Preview changes without committing
          --interactive, -i  Interactive mode with prompts
          --push             Automatically push to remote
          --help, -h         Show this help message
        
        Examples:
          php release.php                    # Auto-release on main branch
          php release.php --branch=develop   # Release from develop branch
          php release.php --dry-run          # Preview without changes
          php release.php -i                 # Interactive mode
        
        HELP;
    }

    public function run(): void
    {
        try {
            $this->log('🚀 Starting release process...', 'info');

            // Pre-flight checks
            $this->validateEnvironment();
            $this->loadComposerFile();
            $this->validateGitState();

            // Analyze commits
            $lastTag = $this->getLastTag();
            $commits = $this->getCommitsSinceTag($lastTag);

            if (empty($commits)) {
                $this->log('⚠️  No commits found since last tag. Nothing to release.', 'warning');
                exit(0);
            }

            // Determine version bump
            $analysis = $this->analyzeCommits($commits);
            $bumpType = $analysis['bumpType'];
            $newVersion = $this->calculateNewVersion($bumpType);

            // Show summary
            $this->showSummary($lastTag, $newVersion, $bumpType, $commits, $analysis);

            // Confirm if interactive
            if ($this->interactive && ! $this->confirm('Proceed with release?')) {
                $this->log('❌ Release cancelled by user.', 'error');
                exit(1);
            }

            if ($this->dryRun) {
                $this->log('🔍 Dry-run mode: No changes will be made.', 'info');
                exit(0);
            }

            // Execute release
            $this->updateComposerVersion($newVersion);
            $this->generateChangelog($lastTag, $newVersion, $commits, $analysis);
            $this->commitChanges($newVersion);
            $this->createTag($newVersion);

            if ($this->autoPush) {
                $this->pushChanges($newVersion);
            }

            $this->log("✅ Successfully released v$newVersion!", 'success');
            $this->showNextSteps($newVersion);
        } catch (Exception $e) {
            $this->log('❌ Error: ' . $e->getMessage(), 'error');
            exit(1);
        }
    }

    private function validateEnvironment(): void
    {
        // Check if git is installed
        exec('git --version 2>&1', $output, $returnCode);
        if ($returnCode !== 0) {
            throw new Exception('Git is not installed or not in PATH');
        }

        // Check if we're in a git repository
        exec('git rev-parse --git-dir 2>&1', $output, $returnCode);
        if ($returnCode !== 0) {
            throw new Exception('Not a git repository');
        }

        // Check if composer.json exists
        if (! file_exists($this->composerFile)) {
            throw new Exception('composer.json not found');
        }
    }

    private function loadComposerFile(): void
    {
        $content = file_get_contents($this->composerFile);
        $this->composer = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON in composer.json: ' . json_last_error_msg());
        }

        $this->currentVersion = $this->composer['version'] ?? '0.0.0';
    }

    private function validateGitState(): void
    {
        // Check for uncommitted changes
        exec('git status --porcelain', $output);
        if (! empty($output)) {
            throw new Exception('Working directory has uncommitted changes. Please commit or stash them first.');
        }

        // Check if branch exists
        exec("git rev-parse --verify $this->branch 2>&1", $output, $returnCode);
        if ($returnCode !== 0) {
            throw new Exception("Branch '$this->branch' does not exist");
        }
    }

    private function getLastTag(): string
    {
        $tag = trim(shell_exec("git describe --tags --abbrev=0 $this->branch 2>/dev/null") ?: '');

        return $tag ?: 'v0.0.0';
    }

    private function getCommitsSinceTag(string $lastTag): array
    {
        $range = $lastTag === 'v0.0.0' ? $this->branch : "$lastTag..$this->branch";
        $output = shell_exec("git log $range --pretty=format:'%H|%s|%b|%an|%ae|%ai' 2>/dev/null");

        if (empty($output)) {
            return [];
        }

        $commits = [];
        $lines = explode("\n", trim($output));

        foreach ($lines as $line) {
            if (empty($line)) {
                continue;
            }

            $parts = explode('|', $line, 6);
            $commits[] = [
                'hash' => $parts[0] ?? '',
                'subject' => $parts[1] ?? '',
                'body' => $parts[2] ?? '',
                'author' => $parts[3] ?? '',
                'email' => $parts[4] ?? '',
                'date' => $parts[5] ?? '',
            ];
        }

        return $commits;
    }

    private function analyzeCommits(array $commits): array
    {
        $bumpType = 'patch';
        $hasBreakingChange = false;
        $categorized = [];
        $breaking = [];

        foreach ($commits as $commit) {
            $subject = $commit['subject'];
            $body = $commit['body'];
            $fullMessage = $subject . "\n" . $body;

            // Check for breaking changes
            if (preg_match('/BREAKING CHANGE/i', $fullMessage)) {
                $hasBreakingChange = true;
                $breaking[] = $commit;
            }

            // Categorize by conventional commit type
            $categorized['other'] = $categorized['other'] ?? [];

            foreach ($this->conventionalTypes as $type => $config) {
                if (preg_match("/^$type(\(.+\))?:/i", $subject)) {
                    $categorized[$type] = $categorized[$type] ?? [];
                    $categorized[$type][] = $commit;

                    if ($config['bump'] === 'minor' && $bumpType === 'patch') {
                        $bumpType = 'minor';
                    }

                    continue 2;
                }
            }

            $categorized['other'][] = $commit;
        }

        if ($hasBreakingChange) {
            $bumpType = 'major';
        }

        return [
            'bumpType' => $bumpType,
            'categorized' => $categorized,
            'breaking' => $breaking,
            'hasBreakingChange' => $hasBreakingChange,
        ];
    }

    private function calculateNewVersion(string $bumpType): string
    {
        [$major, $minor, $patch] = array_map('intval', explode('.', $this->currentVersion));

        switch ($bumpType) {
            case 'major':
                $major++;
                $minor = 0;
                $patch = 0;
                break;
            case 'minor':
                $minor++;
                $patch = 0;
                break;
            case 'patch':
                $patch++;
                break;
        }

        return "$major.$minor.$patch";
    }

    private function showSummary(string $lastTag, string $newVersion, string $bumpType, array $commits, array $analysis): void
    {
        $this->log("\n" . str_repeat('=', 60), 'info');
        $this->log('📊 Release Summary', 'info');
        $this->log(str_repeat('=', 60), 'info');
        $this->log("Branch:          $this->branch", 'info');
        $this->log("Last Tag:        $lastTag", 'info');
        $this->log("Current Version: {$this->currentVersion}", 'info');
        $this->log("New Version:     $newVersion", 'success');
        $this->log('Bump Type:       ' . strtoupper($bumpType), 'warning');
        $this->log('Total Commits:   ' . count($commits), 'info');

        if ($analysis['hasBreakingChange']) {
            $this->log('⚠️  BREAKING CHANGES DETECTED!', 'warning');
        }

        $this->log("\n📝 Commit Breakdown:", 'info');
        foreach ($analysis['categorized'] as $type => $typeCommits) {
            if (empty($typeCommits)) {
                continue;
            }

            $label = $this->conventionalTypes[$type]['label'] ?? '📦 Other';
            $this->log("  $label: " . count($typeCommits), 'info');
        }

        $this->log(str_repeat('=', 60) . "\n", 'info');
    }

    private function updateComposerVersion(string $newVersion): void
    {
        $this->composer['version'] = $newVersion;
        $json = json_encode($this->composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        file_put_contents($this->composerFile, $json . "\n");
        $this->log("✅ Updated composer.json to v$newVersion", 'success');
    }

    private function generateChangelog(string $lastTag, string $newVersion, array $commits, array $analysis): void
    {
        $date = date('Y-m-d');
        $changelog = "## [$newVersion] - $date\n\n";

        // Breaking changes first
        if (! empty($analysis['breaking'])) {
            $changelog .= "### ⚠️ BREAKING CHANGES\n\n";
            foreach ($analysis['breaking'] as $commit) {
                $changelog .= '- ' . $this->formatCommitMessage($commit['subject']) . "\n";
            }
            $changelog .= "\n";
        }

        // Categorized commits
        foreach ($this->conventionalTypes as $type => $config) {
            if (empty($analysis['categorized'][$type])) {
                continue;
            }

            $changelog .= "### {$config['label']}\n\n";
            foreach ($analysis['categorized'][$type] as $commit) {
                $changelog .= '- ' . $this->formatCommitMessage($commit['subject']) . " ({$commit['hash']})\n";
            }
            $changelog .= "\n";
        }

        // Other commits
        if (! empty($analysis['categorized']['other'])) {
            $changelog .= "### 📦 Other Changes\n\n";
            foreach ($analysis['categorized']['other'] as $commit) {
                $changelog .= '- ' . $this->formatCommitMessage($commit['subject']) . " ({$commit['hash']})\n";
            }
            $changelog .= "\n";
        }

        // Prepend to existing changelog
        $existingChangelog = file_exists($this->changelogFile)
            ? file_get_contents($this->changelogFile)
            : '';

        $fullChangelog = "# Changelog\n\n" . $changelog;

        if ($existingChangelog) {
            // Remove existing title if present
            $existingChangelog = preg_replace('/^# Changelog\s*\n+/', '', $existingChangelog);
            $fullChangelog .= $existingChangelog;
        }

        file_put_contents($this->changelogFile, $fullChangelog);
        $this->log('✅ Generated CHANGELOG.md', 'success');
    }

    private function formatCommitMessage(string $message): string
    {
        // Remove conventional commit prefix for cleaner changelog
        $message = preg_replace('/^(feat|fix|docs|style|refactor|perf|test|build|ci|chore|revert)(\(.+\))?:\s*/i', '', $message);

        return ucfirst(trim($message));
    }

    private function commitChanges(string $newVersion): void
    {
        exec("git add $this->composerFile $this->changelogFile");
        exec("git commit -m 'chore(release): v$newVersion [skip ci]' 2>&1", $output, $returnCode);

        if ($returnCode !== 0) {
            throw new Exception('Failed to commit changes: ' . implode("\n", $output));
        }

        $this->log('✅ Committed changes', 'success');
    }

    private function createTag(string $newVersion): void
    {
        $tagMessage = "Release v$newVersion";
        exec("git tag -a v$newVersion -m '$tagMessage' 2>&1", $output, $returnCode);

        if ($returnCode !== 0) {
            throw new Exception('Failed to create tag: ' . implode("\n", $output));
        }

        $this->log("✅ Created tag v$newVersion", 'success');
    }

    private function pushChanges(string $newVersion): void
    {
        // Push branch
        exec("git push origin $this->branch 2>&1", $output, $returnCode);
        if ($returnCode !== 0) {
            $this->log('⚠️  Warning: Failed to push branch - ' . implode("\n", $output), 'warning');
        } else {
            $this->log('✅ Pushed branch to remote', 'success');
        }

        // Push tag
        exec("git push origin v$newVersion 2>&1", $output, $returnCode);
        if ($returnCode !== 0) {
            $this->log('⚠️  Warning: Failed to push tag - ' . implode("\n", $output), 'warning');
        } else {
            $this->log('✅ Pushed tag to remote', 'success');
        }
    }

    private function showNextSteps(string $newVersion): void
    {
        $this->log("\n🎉 Next Steps:", 'info');

        if (! $this->autoPush) {
            $this->log("1. Push changes:  git push origin $this->branch", 'info');
            $this->log("2. Push tag:      git push origin v$newVersion", 'info');
        }

        $this->log('3. Create GitHub release (optional)', 'info');
        $this->log('4. Deploy to production', 'info');
    }

    private function confirm(string $message): bool
    {
        echo "\n$message [y/N]: ";
        $handle = fopen('php://stdin', 'r');
        $line = fgets($handle);
        fclose($handle);

        return strtolower(trim($line)) === 'y';
    }

    private function log(string $message, string $level = 'info'): void
    {
        $colors = [
            'info' => "\033[0m",      // Default
            'success' => "\033[32m",  // Green
            'warning' => "\033[33m",  // Yellow
            'error' => "\033[31m",    // Red
        ];

        $reset = "\033[0m";
        $color = $colors[$level] ?? $colors['info'];

        echo $color . $message . $reset . "\n";
    }
}

// Run the release manager
try {
    $manager = new ReleaseManager($argv);
    $manager->run();
} catch (Exception $e) {
    echo "\033[31m❌ Fatal Error: {$e->getMessage()}\033[0m\n";
    exit(1);
}
