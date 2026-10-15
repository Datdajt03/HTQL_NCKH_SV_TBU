@echo off
setlocal EnableDelayedExpansion
title CAU HINH CO SO DU LIEU NCKH SINH VIEN (XAMPP)

echo ===============================================================================
echo       HE THONG QUAN LY HOAT DONG NGHIEN CUU KHOA HOC SINH VIEN
echo       CONG CU TU DONG CAU HINH DATABASE KHI CHUYEN MAY / CAI DAT MOI
echo ===============================================================================
echo.

set "SCRIPT_DIR=%~dp0"
set "SCHEMA_FILE=%SCRIPT_DIR%database\schema.sql"
set "SAMPLE_FILE=%SCRIPT_DIR%database\sample_data.sql"

if not exist "%SCHEMA_FILE%" goto ERR_NO_SCHEMA

:: 1. TIM DUONG DAN MYSQL.EXE TRONG XAMPP
set "MYSQL_BIN="

if exist "%SCRIPT_DIR%..\..\mysql\bin\mysql.exe" (
    set "MYSQL_BIN=%SCRIPT_DIR%..\..\mysql\bin\mysql.exe"
) else if exist "C:\xampp\mysql\bin\mysql.exe" (
    set "MYSQL_BIN=C:\xampp\mysql\bin\mysql.exe"
) else if exist "D:\xampp\mysql\bin\mysql.exe" (
    set "MYSQL_BIN=D:\xampp\mysql\bin\mysql.exe"
) else if exist "E:\xampp\mysql\bin\mysql.exe" (
    set "MYSQL_BIN=E:\xampp\mysql\bin\mysql.exe"
) else (
    where mysql.exe >nul 2>&1
    if not errorlevel 1 set "MYSQL_BIN=mysql.exe"
)

if "%MYSQL_BIN%"=="" (
    echo [CANH BAO] Khong tu dong tim thay mysql.exe trong thu muc XAMPP chuan!
    set /p "MYSQL_BIN=Vui long nhap duong dan toi mysql.exe (VD: C:\xampp\mysql\bin\mysql.exe): "
)

echo [*] Su dung MySQL tai: %MYSQL_BIN%
echo.

:: 2. XAC DINH THONG TIN KET NOI (MAC DINH XAMPP LA ROOT / KHONG PASSWORD)
set "DB_HOST=127.0.0.1"
set "DB_PORT=3306"
set "DB_USER=root"
set "DB_PASS="

echo Mac dinh XAMPP su dung User: root, Password: [De trong]
set "CONFIRM_PASS=k"
set /p "CONFIRM_PASS=MySQL cua ban co dat mat khau khong? [c/k, mac dinh k]: "

if /i "%CONFIRM_PASS%"=="c" (
    set /p "DB_PASS=Nhap mat khau MySQL: "
)

echo.
echo [*] Dang tao co so du lieu va cac bang chuan 3NF (18 bang)...
echo.

if "%DB_PASS%"=="" (
    "%MYSQL_BIN%" -h %DB_HOST% -P %DB_PORT% -u %DB_USER% < "%SCHEMA_FILE%"
) else (
    "%MYSQL_BIN%" -h %DB_HOST% -P %DB_PORT% -u %DB_USER% -p%DB_PASS% < "%SCHEMA_FILE%"
)

if errorlevel 1 goto ERR_EXEC_SCHEMA

echo [*] Dang nap du lieu mau phong phu (15 Use Cases)...
if exist "%SAMPLE_FILE%" (
    if "%DB_PASS%"=="" (
        "%MYSQL_BIN%" -h %DB_HOST% -P %DB_PORT% -u %DB_USER% < "%SAMPLE_FILE%"
    ) else (
        "%MYSQL_BIN%" -h %DB_HOST% -P %DB_PORT% -u %DB_USER% -p%DB_PASS% < "%SAMPLE_FILE%"
    )
)

if not exist "%SCRIPT_DIR%public\uploads" (
    mkdir "%SCRIPT_DIR%public\uploads"
)

echo.
echo ===============================================================================
echo                KHOI TAO CO SO DU LIEU THANH CONG!
echo ===============================================================================
echo Database name: ql_nckh_sv
echo Encoding:      utf8mb4_unicode_ci
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
echo TRUY CAP WEBSITE:
echo URL: http://localhost/web1/
echo.
echo ===============================================================================
pause
exit /b 0

:ERR_NO_SCHEMA
echo [LOI] Khong tim thay file schema.sql tai: %SCHEMA_FILE%
pause
exit /b 1

:ERR_EXEC_SCHEMA
echo.
echo [LOI] Khong the thuc thi schema.sql!
echo Vui long kiem tra:
echo 1. Dich vu MySQL trong XAMPP da duoc START chua?
echo 2. Mat khau MySQL root da nhap dung chua?
echo.
pause
exit /b 1
