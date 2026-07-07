$ErrorActionPreference = 'Stop'

$gitDir = 'C:\Users\HP\tools\git-portable\cmd'
$ghDir = 'C:\Users\HP\tools\gh-extracted\bin'
$env:Path = "$gitDir;$ghDir;" + $env:Path

Set-Location 'C:\xampp\htdocs\cyraTech'

$repo = 'CodeMaestroPro/Cyra-Tech-Enterprise-Website'
$repoUrl = "https://github.com/$repo.git"

git remote remove origin 2>$null
git remote add origin $repoUrl

Write-Host 'Checking GitHub authentication...'
gh auth status 2>$null
if ($LASTEXITCODE -ne 0) {
    Write-Host 'GitHub login required. Complete device login, then rerun this script.'
    gh auth login --hostname github.com --git-protocol https --web
}

Write-Host "Pushing to $repoUrl ..."
git push -u origin HEAD

Write-Host "Done: https://github.com/$repo"
