# 获取输入参数

# 设置控制台代码页为UTF-8
chcp 65001
Write-Host "正在处理远程地址： $origin  $package"
# 设置控制台输出编码为UTF-8
[Console]::OutputEncoding = [System.Text.Encoding]::UTF8
# 获取当前目录
$currentDir = Get-Location

$origin = git remote get-url origin
Write-Host "远程地址：$origin"

function replaceOrigin{
 param (
        [string]$package,
        [string]$origin
    )
    if ($origin -match '([^/]+)\.git$') {
        $repoName = $matches[1]  # 提取匹配的部分（如 framework）
        Write-Host "原始仓库名：$repoName"

        # 替换为新的包名
        return $origin -replace "$repoName\.git$", "$package.git"

    }

}



# 遍历出当前目录下  packages/*  的子目录
$packages = Get-ChildItem -Path "packages" -Directory | Where-Object { $_.Name -match '^[a-zA-Z0-9_-]+$' }




function initDir{
 param (
        [string]$dir
    )
    if (Test-Path $dir) {
        # 输出当前目录
        Write-Host "删除目录：$dir"
        Remove-Item $dir -Recurse -Force
    }
    # 创建目录
    Write-Host "创建目录：$dir"
    New-Item -Path $dir -ItemType Directory
}




function handlePackage {
 param (
        [string]$package,
        [string]$workDir
    )
    Set-Location $workDir
    # 初始化临时目录
    initDir -dir "$workDir\tmp"

    Set-Location "$workDir\main"
    $origin = git remote get-url origin
    $newOrigin = replaceOrigin -package $package -origin $origin
    Write-Host "新远程地址：$newOrigin"


    # 复制当前目录下的 main 文件夹 到 tmp 文件夹下
    Copy-Item -Path "$workDir\main\*" -Destination "$workDir\tmp" -Recurse -Force


    # 进入到 tmp 目录
    Set-Location "$workDir\tmp"


    # 执行拆包
    Write-Host "执行拆包"
    git filter-repo --subdirectory-filter packages/$package
    Write-Host "设置远程地址"
    git remote add origin $newOrigin
    Write-Host "设置分支"
    git branch -M master
    Write-Host "推送远程"
    git push -uf origin master
    Write-Host "推送所有分支"
    git push --all
    Write-Host "推送Tags"
    git push --tags

    Write-Host "推送完成"

    Set-Location $workDir
    Remove-Item "$workDir\tmp" -Recurse -Force
}



# 进入工作目录

$workDir = $env:USERPROFILE+'\git-subdirectory-workspace'
initDir -dir $workDir
Write-Host "进入工作目录:$workDir"
Set-Location $workDir

Write-Host "下载主包"
# 下载主包
git clone $origin main
$mainDir = "$workDir\main"
Write-Host "主包目录：$mainDir"

# # 输出  $packages
# # 输出每个子目录的名称
foreach ($package in $packages) {
    Write-Host "子目录名称: $($package.Name)"
    handlePackage -package $package.Name -workDir $workDir
}

Write-Host "返回当前目录"
Set-Location $currentDir
exit






