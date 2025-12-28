@echo off
setlocal

set DB=fluensys
set USER=root
set PASS=root
set HOST=127.0.0.1
set PORT=3306

if not exist var\demo mkdir var\demo

set FILE=%~1
if "%FILE%"=="" set FILE=demo.sql

echo Export demo database to var\demo\%FILE%
mysqldump -h %HOST% -P %PORT% -u %USER% -p%PASS% ^
  --single-transaction --routines --triggers --events ^
  --add-drop-table --no-tablespaces ^
  %DB% > var\demo\%FILE%

if errorlevel 1 (
  echo Export failed.
  exit /b 1
)

echo Done.
endlocal
