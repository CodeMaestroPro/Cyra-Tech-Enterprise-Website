$ErrorActionPreference = 'Stop'

$gitDir = 'C:\Users\HP\tools\git-portable\cmd'
$ghDir = 'C:\Users\HP\tools\gh-extracted\bin'
$env:Path = "$gitDir;$ghDir;" + $env:Path

Set-Location 'C:\xampp\htdocs\cyraTech'

$repo = 'CodeMaestroPro/cyra-tech-platform'

Write-Host 'Checking GitHub authentication...'
$auth = gh auth status 2>&1
if ($LASTEXITCODE -ne 0) {
    Write-Host 'GitHub login required.'
    Write-Host 'Complete the device login in your browser, then rerun this script.'
    gh auth login --hostname github.com --git-protocol https --web
}

Write-Host "Creating repository $repo if it does not exist..."
gh repo view $repo 2>$null
if ($LASTEXITCODE -ne 0) {
    gh repo create $repo `
        --public `
        --source . `
        --remote origin `
        --description 'Cyra-Tech Enterprise Platform - Laravel 12, JavaScript, Tailwind CSS 4, MySQL'
} else {
    git remote remove origin 2>$null
    git remote add origin "https://github.com/$repo.git"
}

Write-Host 'Pushing main branch to GitHub...'
git push -u origin main

Write-Host "Done: https://github.com/$repo"
