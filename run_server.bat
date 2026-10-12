@echo off
title Zenith Systems and Services - Web Server
cd /d "%~dp0"

echo ========================================================
echo   ZENITH SYSTEMS AND SERVICES - Web Server
echo   Computer Sales & Service, Sulur, Coimbatore
echo ========================================================
echo.
echo Starting local PHP server on http://127.0.0.1:8000 ...
echo.
echo [1] Customer Website: http://127.0.0.1:8000/index.php
echo [2] Admin Suite:      http://127.0.0.1:8000/admin/login.php
echo.
echo Admin Credentials:  admin / admin123
echo.
echo IMPORTANT: Keep this command prompt window OPEN!
echo (Closing this window will stop the web server)
echo ========================================================
echo.

:: Locate PHP binary
set "PHP_BIN=php"
if exist "C:\tools\php\php.exe" set "PHP_BIN=C:\tools\php\php.exe"
if exist "C:\xampp\php\php.exe" set "PHP_BIN=C:\xampp\php\php.exe"

:: Open browser after 2 seconds delay so the server is fully ready
start "" cmd /c "timeout /t 2 /nobreak >nul & start http://127.0.0.1:8000/index.php"

:: Start PHP built-in server on IPv4 127.0.0.1:8000
"%PHP_BIN%" -S 127.0.0.1:8000 -t "%~dp0"

if %ERRORLEVEL% NEQ 0 (
    echo.
    echo Port 8000 might be in use, trying alternative port 8080...
    start "" cmd /c "timeout /t 2 /nobreak >nul & start http://127.0.0.1:8080/index.php"
    "%PHP_BIN%" -S 127.0.0.1:8080 -t "%~dp0"
)

pause
