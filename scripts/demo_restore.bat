@echo off
setlocal

set DB=fluensys
set USER=root
set PASS=root
set HOST=127.0.0.1
set PORT=3306

set FILE=%~1
if "%FILE%"=="" set FILE=demo.sql

if not exist var\demo\%FILE% (
  echo Missing var\demo\%FILE%
  exit /b 1
)

echo Drop and recreate database %DB%
mysql -h %HOST% -P %PORT% -u %USER% -p%PASS% -e "DROP DATABASE IF EXISTS %DB%; CREATE DATABASE %DB% CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

if errorlevel 1 (
  echo Drop/Create failed.
  exit /b 1
)

echo Import demo snapshot from var\demo\%FILE%
mysql -h %HOST% -P %PORT% -u %USER% -p%PASS% %DB% < var\demo\%FILE%

if errorlevel 1 (
  echo Import failed.
  exit /b 1
)

echo Done.
endlocal
