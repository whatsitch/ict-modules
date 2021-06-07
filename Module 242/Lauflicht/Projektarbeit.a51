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

 

/*----------  Deklarationen ----------*/

output    	equ     P2 ;P2 8 Ausgänge
input     	equ     P3 ;P3 8 Eingänge    
status    	data    20h
light     	data    21h
speed		data	22h
direction	data	23h
            		
                    

 

/*----------  Intitialisierung ----------*/
        			ORG        0000h                ;Startadresse
        			jmp        init

 

        			ORG        0100h                ;Programmanfang
init:   			MOV        WDTCN,#0DEh
        			MOV        WDTCN,#0ADh          ;disable Watchdog
        			MOV        P2MDOUT,#0FFh        ;P2 8 Ausgänge push/pull
        			MOV        P3MDOUT,#000h        ;P3 8 Eingänge
        			MOV        XBR2,#040h           ;enable crossbar (Koppelfeld)

 

/*----------  Hauptprogramm ----------*/

main:    			MOV     output,#00h
		 			MOV		input, #00h            
		 			MOV		light, #00b
					MOV		direction, #00h

 

loop:				MOV        	A,input                
     				MOV        	status, A
					MOV			P7, status
					JBC			status.4, setBackwardDirection
					ACALL		setForwardDirection
					  
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

DELAY:			   	MOV 		R7, #10D
Timer:				MOV			TMOD, #01h
					MOV			TH0, #0DBH
					MOV			TL0, #0FFH
					SETB		TCON.4
TimerOverflow:		JNB			TCON.5, TimerOverflow
					CLR			TCON.4
					CLR			TCON.5
					DJNZ		R7, Timer
					RET	

/*---------- BACKWARD / FORWARD DIRECTION ----------*/
		
setBackwardDirection:	MOV			A, direction
						MOV			A, #11111111b
						MOV			direction, A
						MOV			P7, direction
						JMP			loopStatus

setForwardDirection:	MOV			A, direction
						ANL			A, #00h
						MOV			direction, A
						JMP			loopStatus
					
moveForward:			mov			A, light
						RL			A
						mov			light, A
						cpl			light.0
						mov			output, light
						jmp			lightOutput
					
moveBackward:			mov			A, light
						RR			A
						mov			light, A
						cpl			light.7
						jmp			lightOutput
											
/*---------- STATUS ----------*/

stop:				mov			P7, #00000000b
					mov			output, #00h
					mov			light, #00h
					jmp			loop


move:				mov			P7, #00000001b
					mov			A, direction
					JZ			moveForward
					JMP			moveBackward
					mov			light, A
lightOutput:		mov			output, light
					jmp			loop
		

pause:				mov			P7, #00000010b
					jmp			loop
 

	    
END

