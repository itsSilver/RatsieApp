CREATE OR REPLACE FUNCTION ticketIsFinished
( p_ticketId IN NUMBER )
RETURN INTEGER
AS
  CURSOR c_bets IS
    SELECT *
    FROM Bets
    WHERE p_ticketId = ticketId;
           
  r_bets   c_bets%ROWTYPE;
BEGIN
  OPEN c_bets;
  IF c_bets%NOTFOUND THEN
    RETURN 0;
  END IF;
  LOOP
    FETCH c_bets INTO r_bets;
    EXIT WHEN c_bets%NOTFOUND;
      IF betIsFinished(r_bets.betId) = 0 THEN
        RETURN 0;
      END IF; 
  END LOOP;  
  CLOSE c_bets;                
  RETURN 1;
 END ticketIsFinished;
 /