 CREATE OR REPLACE TYPE pozitionare IS VARRAY(6) OF NUMBER(8,2);
/
CREATE OR REPLACE FUNCTION calc_receResult
(rat1Odd IN NUMBER,
 rat2Odd IN NUMBER,
 rat3Odd IN NUMBER,
 rat4Odd IN NUMBER,
 rat5Odd IN NUMBER,
 rat6Odd IN NUMBER)
 RETURN pozitionare
 AS

  reverse_1 number(8,2);  
  reverse_2 number(8,2);
  reverse_3 number(8,2);
  reverse_4 number(8,2);
  reverse_5 number(8,2);
  reverse_6 number(8,2);
  isSet1 number(2) := 0;
  isSet2 number(2) := 0;
  isSet3 number(2) := 0;
  isSet4 number(2) := 0;
  isSet5 number(2) := 0;
  isSet6 number(2) := 0;
  suma1 number(8,2);
  suma2 number(8,2);
  suma3 number(8,2);
  suma4 number(8,2);
  suma5 number(8,2);
  suma6 number(8,2);
  OK number(4) := 0;
  randPick number(8);
  contor number(8) := 0;
  clasament pozitionare;
BEGIN
  reverse_1 := ROUND((1/rat1Odd),2)*100;
  reverse_2 := ROUND((1/rat2Odd),2)*100;
  reverse_3 := ROUND((1/rat3Odd),2)*100;
  reverse_4 := ROUND((1/rat4Odd),2)*100;
  reverse_5 := ROUND((1/rat5Odd),2)*100;
  reverse_6 := ROUND((1/rat6Odd),2)*100;
  suma1 := 0 + reverse_1;
  suma2 := suma1 + reverse_2;
  suma3 := suma2 + reverse_3;
  suma4 := suma3 + reverse_4;
  suma5 := suma4 + reverse_5;
  suma6 := suma5 + reverse_6;
  clasament := pozitionare(0.00, 0.00, 0.00, 0.00, 0.00, 0.00);
  WHILE OK<6
  LOOP
    select dbms_random.value(0,suma6) INTO randPick from dual;
    IF randPick > 0 AND randPick <= suma1 AND isSet1 = 0 THEN
       isSet1 := 1;
       OK := OK + 1;
       clasament(OK) := 1;
    END IF;
    IF randPick > suma1 AND randPick <= suma2 AND isSet2 = 0 THEN
       isSet2 := 1;
       OK := OK + 1;
       clasament(OK) := 2;
    END IF;
    IF randPick > suma2 AND randPick <= suma3 AND isSet3 = 0 THEN
       isSet3 := 1;
       OK := OK + 1;
       clasament(OK) := 3;
    END IF;
    IF randPick > suma3 AND randPick <= suma4 AND isSet4 = 0 THEN
       isSet4 := 1;
       OK := OK + 1;
       clasament(OK) := 4;
    END IF;
    IF randPick > suma4 AND randPick <= suma5 AND isSet5 = 0 THEN
       isSet5 := 1;
       OK := OK + 1;
       clasament(OK) := 5;
    END IF;
    IF randPick > suma5 AND randPick <= suma6 AND isSet6 = 0 THEN
       isSet6 := 1;
       OK := OK + 1;
       clasament(OK) := 6;
    END IF;
  END LOOP;
 RETURN clasament;
END calc_receResult;
/