@echo off

REM 创建软链接：storage 指向 ../vendor/orchestra/testbench-core/laravel/storage
mklink /D "storage" "..\vendor\orchestra\testbench-core\laravel\storage"

REM 创建 storage/framework 下的子目录
mkdir "storage\framework\cache"
mkdir "storage\framework\sessions"
mkdir "storage\framework\views"
mkdir "storage\framework\testing"
mkdir "storage\logs"