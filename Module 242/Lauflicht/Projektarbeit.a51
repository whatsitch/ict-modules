/*------------------------------------------------------------
-------------- M242 Praktische Umsetzungsarbeit --------------
--------------------------------------------------------------
Source-File:	Projektarbeit.a51
Autor:			Marco Eugster, Noel Schneider
Datum:			06.06.2021
Version:		1.0
Beschreibung:	Lauflichtsteuerung mit MESA Hardware
Eingaben:		8 Schalter S0 bis S7
Ausgaben:		8 LEDs PA0 bis PA7
------------------------------------------------------------*/
    
$TITLE (Lauflichtsteuerung)
$NOLIST
$NOMOD51
$INCLUDE (C8051F020.h)                ;hier werden alle Bezeichnungen definiert
$LIST
NAME Lauflichtsteuerung

 

/*----------  declaration ----------*/

output    	equ     P2 ; output
input     	equ     P3 ; input    
status    	data    20h
light     	data    21h
speed		data	22h
direction	data	23h
lightWidth	data	24h            		
lightStatus	data	25h                    

 

/*----------  initialisation ----------*/

        			ORG        0000h                ;Startaddress
        			JMP        init

 

        			ORG        	0100h                ;programme start
init:   			MOV        	WDTCN,#0DEh
        			MOV        	WDTCN,#0ADh          ;disable Watchdog
        			MOV        	P2MDOUT,#0FFh        ;P2 8 outputs push/pull
        			MOV        	P3MDOUT,#000h        ;P3 8 inputs
        			MOV        	XBR2,#040h           ;enable crossbar (Koppelfeld)
					JMP			main


/*---------- LIGHT EFFECT WIDTH ----------*/ 

setLightWidth:			ANL			A, #01000000b
						SWAP		A
						RR			A
						RR			A
						MOV			lightWidth, A
						JZ			defaultSpeed
						JNB			lightStatus.7, enableCustomLightWidth
						JNB			lightWidth.0, disableCustomLightWidth
						JMP			defaultSpeed

enableCustomLightWidth:	SETB		lightStatus.7
						CALL		turnLEDsOff
						MOV			light, #00000001b
						JMP			defaultSpeed

disableCustomLightWidth:	CALL	turnLEDsOff
							MOV		lightStatus, #00000000b
							MOV		light, #00h
							JMP		defaultSpeed
							

/*----------  Hauptprogramm ----------*/

main:    			MOV     	output,#00h          
		 			MOV			light, #00b
					MOV			direction, #00h
					MOV			lightWidth, #00h

loop:				MOV        	A,input                
     				MOV        	status, A
					JMP			setLightWidth 
defaultSpeed:		MOV			speed, #10D
					JBC			status.5, setBackwardDirection
					JMP			setForwardDirection
loopSpeed:			JBC			status.2, setVerySlowSpeed
					JBC			status.3, setSlowSpeed
					JBC			status.4, setHighSpeed
loopStatus:        	ANL        	status, #00000011b
					ACALL		DELAY
					MOV			A, status
					JZ			stop
					DEC			A
					JZ			move
					DEC			A
					DEC			A
					JMP			pause


/*---------- DELAY ----------*/

DELAY:			   	MOV 		R7, speed
Timer:				MOV			TMOD, #01h	; time mode 8-bit register
					MOV			TL0, #0FFH	; timer 0 low byte
					MOV			TH0, #0DBH	; timer 0 high byte
					SETB		TCON.4		; timer control
TimerOverflow:		JNB			TCON.5, TimerOverflow
					CLR			TCON.4
					CLR			TCON.5
					DJNZ		R7, Timer
					RET


/*---------- CHANGE SPEED ----------*/

setVerySlowSpeed:		MOV			speed, #30D
						JMP			loopStatus

setSlowSpeed:			MOV			speed, #15D
						JMP			loopStatus

setHighSpeed:			MOV			speed, #1D
						JMP			loopStatus
							

/*---------- BACKWARD / FORWARD DIRECTION ----------*/
		
setBackwardDirection:	MOV			A, direction
						MOV			A, #11111111b
						MOV			direction, A
						MOV			P7, direction
						JMP			loopSpeed

setForwardDirection:	MOV			A, direction
						ANL			A, #00h
						MOV			direction, A
						JMP			loopSpeed
					
moveForward:			MOV			A, light
						RL			A
						MOV			light, A
						JB			lightWidth.0, smallLightWidth
										
						CPL			light.0
moveOutput:				MOV			output, light
						JMP			lightOutput
					
moveBackward:			MOV			A, light
						RR			A
						MOV			light, A
						JB			lightWidth.0, smallLightWidth
						CPL			light.7
						JMP			lightOutput

/*--------------------------------------------------*/
													 
smallLightWidth:		RL			A
						ANL			A, #10000001b 
						CPL			A
						ANL			light, A
						RL			A
						jmp			moveOutput


turnLEDsOff:		MOV			output, #00h
					MOV			light, #00h
					RET		
						
										
/*---------- STATUS ----------*/

stop:				ACALL		turnLEDsOff
					JMP			loop


move:				MOV			A, direction
					JZ			moveForward
					JMP			moveBackward
					MOV			light, A
lightOutput:		MOV			output, light
					JMP			loop
		

pause:				JMP			loop
 

	    
END

