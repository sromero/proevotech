@echo off
cls

:Menu
color 74
color 30 
echo ================================== 
echo = 
echo = 	   HOLA SANTINO..... 
echo =  
echo ================================== 
echo. 
echo. 
echo Fecha actual:                 %DATE% 
echo Hora actual:                  %TIME%    
echo Nombre del equipo:            %COMPUTERNAME% 
echo Nombre del usuario:           %USERNAME% 
echo.
Title Menu Gestor de Encuesta 
		echo SELECCIONE OPCION
		echo.
		echo 1. COPIAR A WWW-AppServer
		echo 2. ENCRIPTAR
		echo 3. COPIAR A MySQL
		echo 3. SALIR
		echo.
		echo -*/-*/-*/-*/-*/-*/-*/-*/-*/-*/-*/
		echo.

	set /p var=
	if %var%==1 goto :Primero
	if %var%==2 goto :Segundo
	if %var%==3 goto exit
	if %var% GTR 3 echo Error
	goto :Menu

	:Primero
	cls 
	color a
	Echo OPCION DE COPIADO A WWW-AppServer
	Echo.
		robocopy C:\Users\sromero.NWSN\Tools\ProyectosPlanta3\trunk\GestorEncuesta C:\AppServ\www\gestorEncuestas /E /XC /XN /XO
	Echo VOLVER AL MENU ?
	Pause>Nul
	cls
	goto :Menu

	:Segundo
	cls 
	color 1a
	Echo Esta es la Segunda Opcion
	Echo Precione una tecla para volver al menu
	runas /user:# "" >nul 2>&1
	Pause>Nul
	goto :Menu
	
	:bucle
		echo 1001010010010100100101001010010010100100101001010010010100100101001010010010
		echo 0110101101101011011010110101101101011011010110101101101011011010110110101101
goto bucle

@echo off


