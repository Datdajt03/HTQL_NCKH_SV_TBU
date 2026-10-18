@echo off
setlocal EnableDelayedExpansion
title CAU HINH CO SO DU LIEU NCKH SINH VIEN (XAMPP - TU DONG NHAN DIEN O DIA)

echo ===============================================================================
echo       HE THONG QUAN LY HOAT DONG NGHIEN CUU KHOA HOC SINH VIEN (TBU)
echo       CONG CU TU DONG CAU HINH DATABASE VA DUONG DAN CHO MOI O DIA (A, C, D...)
echo ===============================================================================
echo.

set "SCRIPT_DIR=%~dp0"
set "CURRENT_DRIVE=%~d0"
set "SCHEMA_FILE=%SCRIPT_DIR%database\schema.sql"
set "SAMPLE_FILE=%SCRIPT_DIR%database\sample_data.sql"
set "CONFIG_FILE=%SCRIPT_DIR%app\Config\database_custom.php"

echo [*] Thu muc du an hien tai: %SCRIPT_DIR%
echo [*] O dia hien tai         : %CURRENT_DRIVE%
echo.

if not exist "%SCHEMA_FILE%" goto ERR_NO_SCHEMA

rem ===============================================================================
rem 1. TU DONG QUET VA NHAN DIEN XAMPP VA MYSQL.EXE TREN MOI O DIA
rem ===============================================================================
set "MYSQL_BIN="
set "XAMPP_ROOT="
set "PHP_BIN="

echo [*] Dang quet he thong de tim thu muc XAMPP va mysql.exe...

rem Cach 1: Do nguoc tu thu muc script len thu muc goc de tim XAMPP cha
set "CUR_DIR=%SCRIPT_DIR%"
:LOOP_FIND_XAMPP
if exist "%CUR_DIR%mysql\bin\mysql.exe" (
    set "MYSQL_BIN=%CUR_DIR%mysql\bin\mysql.exe"
    set "XAMPP_ROOT=%CUR_DIR%"
    goto FOUND_MYSQL
)
set "PARENT_DIR="
for %%I in ("%CUR_DIR%..") do set "PARENT_DIR=%%~fI\"
if "%PARENT_DIR%"=="" goto AFTER_LOOP_FIND
if "%PARENT_DIR%"=="%CUR_DIR%" goto AFTER_LOOP_FIND
set "CUR_DIR=%PARENT_DIR%"
goto LOOP_FIND_XAMPP

:AFTER_LOOP_FIND
rem Cach 2: Kiem tra truc tiep thu muc xampp tren o dia hien tai
if not defined MYSQL_BIN if exist "%CURRENT_DRIVE%\xampp\mysql\bin\mysql.exe" (
    set "MYSQL_BIN=%CURRENT_DRIVE%\xampp\mysql\bin\mysql.exe"
    set "XAMPP_ROOT=%CURRENT_DRIVE%\xampp\"
)

rem Cach 3: Quet lan luot cac phan vung o dia pho bien (A, C, D, E, F)
if not defined MYSQL_BIN if exist "A:\xampp\mysql\bin\mysql.exe" (
    set "MYSQL_BIN=A:\xampp\mysql\bin\mysql.exe"
    set "XAMPP_ROOT=A:\xampp\"
)
if not defined MYSQL_BIN if exist "C:\xampp\mysql\bin\mysql.exe" (
    set "MYSQL_BIN=C:\xampp\mysql\bin\mysql.exe"
    set "XAMPP_ROOT=C:\xampp\"
)
if not defined MYSQL_BIN if exist "D:\xampp\mysql\bin\mysql.exe" (
    set "MYSQL_BIN=D:\xampp\mysql\bin\mysql.exe"
    set "XAMPP_ROOT=D:\xampp\"
)
if not defined MYSQL_BIN if exist "E:\xampp\mysql\bin\mysql.exe" (
    set "MYSQL_BIN=E:\xampp\mysql\bin\mysql.exe"
    set "XAMPP_ROOT=E:\xampp\"
)
if not defined MYSQL_BIN if exist "F:\xampp\mysql\bin\mysql.exe" (
    set "MYSQL_BIN=F:\xampp\mysql\bin\mysql.exe"
    set "XAMPP_ROOT=F:\xampp\"
)

rem Cach 4: Kiem tra bien moi truong PATH
if not defined MYSQL_BIN (
    where mysql.exe >nul 2>&1
    if not errorlevel 1 (
        for /f "delims=" %%I in ('where mysql.exe 2^>nul') do (
            if not defined MYSQL_BIN set "MYSQL_BIN=%%I"
        )
    )
)

:FOUND_MYSQL
rem Tim PHP neu co
if defined XAMPP_ROOT (
    if exist "!XAMPP_ROOT!php\php.exe" (
        set "PHP_BIN=!XAMPP_ROOT!php\php.exe"
    )
)

rem Neu van chua tim thay, yeu cau nguoi dung nhap tay
if not defined MYSQL_BIN (
    echo [CANH BAO] Khong the tu dong tim thay mysql.exe trong cac duong dan chuan!
    echo Goi y:
    echo   - May chinh [o A]: A:\xampp\mysql\bin\mysql.exe
    echo   - May phu   [o C]: C:\xampp\mysql\bin\mysql.exe
    set /p "MYSQL_BIN=Vui long nhap duong dan toi mysql.exe: "
)

if defined XAMPP_ROOT (
    echo [OK] Phat hien XAMPP tai : !XAMPP_ROOT!
)
echo [OK] Duong dan mysql.exe : !MYSQL_BIN!
if defined PHP_BIN (
    echo [OK] Duong dan php.exe   : !PHP_BIN!
)
echo.

rem ===============================================================================
rem 2. XAC DINH THONG TIN KET NOI CO SO DU LIEU
rem ===============================================================================
set "DB_HOST=127.0.0.1"
set "DB_PORT=3306"
set "DB_NAME=ql_nckh_sv"
set "DB_USER=root"
set "DB_PASS="

echo Mac dinh XAMPP su dung: Host=127.0.0.1, Port=3306, User=root, Password=[De trong]
set "CONFIRM_PASS=k"
set /p "CONFIRM_PASS=MySQL cua ban co dat mat khau khong? [c/k, mac dinh k]: "

if /i "!CONFIRM_PASS:~0,1!"=="c" (
    set /p "DB_PASS=Nhap mat khau MySQL: "
)

echo.
echo [*] Dang kiem tra ket noi den dich vu MySQL...
set "PASS_PARAM="
if not "%DB_PASS%"=="" set "PASS_PARAM=-p%DB_PASS%"

"%MYSQL_BIN%" -h %DB_HOST% -P %DB_PORT% -u %DB_USER% %PASS_PARAM% -e "SELECT 1;" >nul 2>&1
if errorlevel 1 goto ERR_EXEC_SCHEMA

echo [OK] Ket noi MySQL thanh cong!
echo.

rem ===============================================================================
rem 3. KHOI TAO DATABASE, BANG VA DU LIEU MAU
rem ===============================================================================
echo [*] Dang tao co so du lieu va 18 bang chuan 3NF tu schema.sql...
"%MYSQL_BIN%" --default-character-set=utf8mb4 -h %DB_HOST% -P %DB_PORT% -u %DB_USER% %PASS_PARAM% < "%SCHEMA_FILE%"
if errorlevel 1 goto ERR_EXEC_SCHEMA

echo [OK] Tao co so du lieu va cac bang thanh cong!
echo.

if not exist "%SAMPLE_FILE%" goto SKIP_SAMPLE
echo [*] Dang nap du lieu mau phong phu [15 Use Cases]...
"%MYSQL_BIN%" --default-character-set=utf8mb4 -h %DB_HOST% -P %DB_PORT% -u %DB_USER% %PASS_PARAM% < "%SAMPLE_FILE%"
if errorlevel 1 (
    echo [CANH BAO] Co canh bao khi nap du lieu mau, he thong van tiep tuc.
) else (
    echo [OK] Nap du lieu mau thanh cong.
)
:SKIP_SAMPLE

rem Tao thu muc uploads neu chua co
if not exist "%SCRIPT_DIR%public\uploads" (
    mkdir "%SCRIPT_DIR%public\uploads"
)

rem ===============================================================================
rem 4. DONG BO FILE CAU HINH DATABASE CHO SOURCE CODE PHP
rem ===============================================================================
echo [*] Dang dong bo cau hinh ket noi Database vao ma nguon PHP...
if not exist "%SCRIPT_DIR%app\Config" mkdir "%SCRIPT_DIR%app\Config"
(
    echo ^<?php
    echo // Tu dong sinh boi setup_database.bat
    echo return [
    echo     'db' =^> [
    echo         'host' =^> '%DB_HOST%',
    echo         'port' =^> '%DB_PORT%',
    echo         'database' =^> '%DB_NAME%',
    echo         'username' =^> '%DB_USER%',
    echo         'password' =^> '%DB_PASS%',
    echo         'charset' =^> 'utf8mb4'
    echo     ]
    echo ];
) > "%CONFIG_FILE%"
echo [OK] Da cap nhat file cau hinh: app/Config/database_custom.php

rem ===============================================================================
rem 5. TINH TOAN URL TRUY CAP WEBSITE DUA THEO VI TRI TRONG HTDOCS
rem ===============================================================================
set "WEB_URL="
echo !SCRIPT_DIR! | findstr /i "\\htdocs\\" >nul
if not errorlevel 1 (
    set "REL_PATH=!SCRIPT_DIR:*\htdocs\=!"
    set "REL_PATH=!REL_PATH:\=/!"
    set "WEB_URL=http://localhost/!REL_PATH!"
) else (
    set "WEB_URL=http://localhost/web1/HTQL_NCKH_SV_TBU/"
)

echo.
echo ===============================================================================
echo                KHOI TAO HE THONG VA CO SO DU LIEU THANH CONG!
echo ===============================================================================
echo Database name: %DB_NAME%
echo Bang ma:       utf8mb4_unicode_ci
echo.
echo DANH SACH TAI KHOAN MAU (Mat khau chung: 123456):
echo -------------------------------------------------------------------------------
echo [1] CAN BO QUAN LY:
echo     - Ma: CB001 (Truong phong QLKH) -- Mat khau: 123456
echo     - Ma: CB002 (Chuyen vien NCKH)  -- Mat khau: 123456
echo     - Ma: ADMIN (Quan tri he thong) -- Mat khau: 123456
echo.
echo [2] GIANG VIEN HUONG DAN / HOI DONG:
echo     - Ma: GV001 (TS. Le Hoang Nam)        -- Mat khau: 123456
echo     - Ma: GV002 (PGS.TS. Pham Thi Huong)  -- Mat khau: 123456
echo     - Ma: GV003 (TS. Vu Minh Duc)         -- Mat khau: 123456
echo.
echo [3] SINH VIEN:
echo     - Ma: SV001 (Nguyen Huy Hoang - Truong nhom) -- Mat khau: 123456
echo     - Ma: SV002, SV003, SV004, SV005, SV006      -- Mat khau: 123456
echo -------------------------------------------------------------------------------
echo.
echo DUONG DAN TRUY CAP WEBSITE CUA BAN:
echo [+] Trang chu he thong: !WEB_URL!
echo [+] Trang web setup   : !WEB_URL!setup.php
echo.
echo ===============================================================================
set "OPEN_BROWSER=c"
set /p "OPEN_BROWSER=Ban co muon mo he thong tren trinh duyet ngay bay gio khong? [c/k, mac dinh c]: "
if /i not "!OPEN_BROWSER:~0,1!"=="c" goto SKIP_OPEN_BROWSER
echo [*] Dang khoi chay trinh duyet...
start "" "!WEB_URL!"
:SKIP_OPEN_BROWSER

echo.
echo Hoan tat! Nhan phim bat ky de thoat...
pause >nul
exit /b 0

rem ===============================================================================
rem XU LY CAC TRUONG HOP LOI
rem ===============================================================================
:ERR_NO_SCHEMA
echo.
echo [LOI] Khong tim thay file schema.sql tai: %SCHEMA_FILE%
echo Vui long dam bao thu muc database/ co day du schema.sql truoc khi chay script.
pause
exit /b 1

:ERR_EXEC_SCHEMA
echo.
echo ===============================================================================
echo [LOI] KHONG THE KET NOI HOAC THUC THI SQL TREN MYSQL!
echo ===============================================================================
echo Vui long kiem tra cac van de sau:
echo 1. Dich vu MySQL trong XAMPP da duoc [Start] chua?
if defined XAMPP_ROOT (
    echo    - Hay mo Control Panel tai: !XAMPP_ROOT!xampp-control.exe
    echo    - Nhan nut [Start] tai module MySQL.
) else (
    echo    - Hay kiem tra XAMPP Control Panel tren o dia %CURRENT_DRIVE% hoac o C, nhan [Start] MySQL.
)
echo 2. Mat khau MySQL (root) ban nhap da dung chua? (Neu khong dat mat khau, chon 'k').
echo 3. Cong ket noi (Port %DB_PORT%) co dang bi chiem boi ung dung khac khong?
echo ===============================================================================
echo.
pause
exit /b 1
