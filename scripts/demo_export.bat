@echo off
setlocal

set DB=fluensys
set USER=root
set PASS=root
set HOST=127.0.0.1
set PORT=3306

if not exist demo mkdir demo

set FILE=%~1
if "%FILE%"=="" set FILE=export.sql

echo Export demo database to demo\%FILE%
mysqldump -h %HOST% -P %PORT% -u %USER% -p%PASS% ^
  --single-transaction --routines --triggers --events ^
  --add-drop-table --no-tablespaces ^
  %DB% > demo\%FILE%

if errorlevel 1 (
  echo Export failed.
  exit /b 1
)

echo Done.
endlocal
