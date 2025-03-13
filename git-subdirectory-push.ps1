# 获取输入参数
param (
    [string]$package,
    [string]$origin
)
# 设置控制台代码页为UTF-8
chcp 65001
Write-Host "正在处理远程地址： $origin  $package"
# 设置控制台输出编码为UTF-8
[Console]::OutputEncoding = [System.Text.Encoding]::UTF8
# 获取当前目录
$currentDir = Get-Location

# 检查 $origin 是否为空
if ([string]::IsNullOrEmpty($origin)) {
    # 获取当前目录的 Git 远程地址
    $origin = git remote get-url origin
    Write-Host "未提供远程地址，使用当前目录的远程地址：$origin"
}





# 进入到家目录
# 进入家目录
Set-Location $env:USERPROFILE

$workDir = $env:USERPROFILE+'/_git_subdirectory_push'

Write-Host "当前目录：$workDir"

# 输出当前目录
Write-Host "当前目录： $(Get-Location)"

# 判断 目录 _git_sub_push 目录是否存在  如果存在就删除
if (Test-Path $workDir) {
    # 输出当前目录
    Write-Host "删除目录：$workDir"
    Remove-Item $workDir -Recurse -Force
}

# 把远程地址  git clone 远程地址  到  _git_sub_push 目录
git clone $origin $workDir

# 进入工作目录
Set-Location $workDir

git filter-repo --subdirectory-filter packages/$package

# 把  远程地址 git@github.com:red-jasmine/framework.git 替换为 git@github.com:red-jasmine/$package.git
if ($origin -match '([^/]+)\.git$') {
    $repoName = $matches[1]  # 提取匹配的部分（如 framework）
    Write-Host "原始仓库名：$repoName"

    # 替换为新的包名
    $newOrigin = $origin -replace "$repoName\.git$", "$package.git"
    Write-Host "替换后的远程地址：$newOrigin"
} else {
    Write-Host "远程地址格式不匹配"
}

Write-Host "设置远程地址"
git remote add origin $newOrigin

git branch -M master
Write-Host "推送远程"
git push -uf origin master

git push --all

git push --tags

Write-Host "推送完成"

Write-Host "删除工作目录"

Set-Location $env:USERPROFILE
Remove-Item $workDir -Recurse -Force

Set-Location $currentDir