;********************************************************************************
; P R O G R A M M	Vorlage
;********************************************************************************
; Source-File:		Vorlage.a51
; Autor:			Erwin Wohler
; Datum:			31.01.2013
; Version:			1.0
; Beschreibung:		Gibt Zustand der Schalter an die LEDs aus
; Eingaben:			8 Schalter S0 bis S7
; Ausgaben:			8 LEDs PA0 bis PA7
;********************************************************************************
 	
$TITLE (Vorlage)
$NOLIST
$NOMOD51
$INCLUDE (C8051F020.h)				;hier werden alle Bezeichnungen definiert
$LIST

NAME Vorlage

;----- Deklarationen
input	equ		P3					;P3 8 Eingänge
output	equ		P2					;P2 8 Ausgänge

;----- Intitialisierung
		ORG		0000h				;Startadresse
		jmp		init

		ORG		0100h				;Programmanfang
init:  	mov		WDTCN,#0DEh
		mov		WDTCN,#0ADh			;disable Watchdog
	  	mov		P2MDOUT,#0FFh		;P2 8 Ausgänge push/pull
		mov		P3MDOUT,#000h		;P3 8 Eingänge
		mov		XBR2,#040h			;enable crossbar (Koppelfeld)

;------ Hauptprogramm
main:	mov		output,#00h			;LEDs dunkel schalten

loop:	mov		A,#0fh				;A,input			;Schalter einlesen
		mov		P5,A				;Ausgabe an LEDs
		jmp		loop				;Endlosschleife

		END
