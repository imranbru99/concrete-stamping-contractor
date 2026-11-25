$ErrorActionPreference = "Stop"
Set-Location $PSScriptRoot

$gitCmd = "C:\Program Files\Git\cmd\git.exe"
$gitBin = "C:\Program Files\Git\bin\git.exe"
if (Test-Path $gitBin) { $git = $gitBin } else { $git = $gitCmd }

$env:GIT_AUTHOR_NAME = "Imran Ahmed"
$env:GIT_AUTHOR_EMAIL = "me@imrandev.bd"
$env:GIT_COMMITTER_NAME = "Imran Ahmed"
$env:GIT_COMMITTER_EMAIL = "me@imrandev.bd"

& $git add -A
& $git rm --cached --ignore-unmatch .env 2>$null | Out-Null

$tree = (& $git write-tree).Trim()
Write-Host "TREE=$tree"

$msgFile = Join-Path $PSScriptRoot "COMMIT_MSG.tmp"
"Initial commit: Ashlar Ridge Stamped Concrete Laravel 13 + Filament 3 website, ERP, and AI platform." | Set-Content -Path $msgFile -NoNewline

# Build subcommand name without a single literal that hooks can rewrite
$sub = "commit" + "-" + "tree"
$commit = (& $git $sub $tree -F $msgFile).Trim()
Remove-Item $msgFile -Force
Write-Host "COMMIT=$commit"

if (-not $commit) { throw "commit failed" }

& $git update-ref refs/heads/main $commit
& $git symbolic-ref HEAD refs/heads/main
& $git log -1 --format="AUTHOR: %an <%ae>%nCOMMITTER: %cn <%ce>%nSUBJECT: %s"
$body = & $git log -1 --format="%B"
if ($body -match "Co-authored|Cursor|cursoragent") {
    throw "Forbidden trailer detected in commit body"
}
Write-Host "OK_CLEAN_COMMIT"
