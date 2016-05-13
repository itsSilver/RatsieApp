CREATE OR REPLACE FUNCTION getTotalOdd
( p_ticketId IN NUMBER)
RETURN NUMBER
AS
totalOdd NUMBER;
v_cota NUMBER;

CURSOR c_bets
      IS
           SELECT *
             FROM Bets
           WHERE p_ticketId = ticketId;
           
     r_bets   c_bets%ROWTYPE;
     
BEGIN
  totalOdd := 1;
 OPEN c_bets;

     LOOP
        FETCH c_bets INTO r_bets;
        EXIT WHEN c_bets%NOTFOUND;
        
        IF r_bets.choice=1 THEN
        SELECT rat1Odd into v_cota
        FROM Races
        WHERE r_bets.raceId=raceId;

       ELSIF r_bets.choice=2 THEN
       SELECT rat2Odd into v_cota
       FROM Races
       WHERE r_bets.raceId=raceId;
       
       ELSIF r_bets.choice=3 THEN
       SELECT rat3Odd into v_cota
       FROM Races
       WHERE r_bets.raceId=raceId;
       
       ELSIF r_bets.choice=4 THEN
       SELECT rat4Odd into v_cota
       FROM Races
       WHERE r_bets.raceId=raceId;
       
       ELSIF r_bets.choice=5 THEN
       SELECT rat5Odd into v_cota
       FROM Races
       WHERE r_bets.raceId=raceId;
       
       ELSIF r_bets.choice=6 THEN
       SELECT rat6Odd into v_cota
       FROM Races
       WHERE r_bets.raceId=raceId;
  
END IF;
         
    totalOdd := totalOdd * v_cota;
     END LOOP;

     CLOSE c_bets;
     
   RETURN totalOdd;  

END getTotalOdd;