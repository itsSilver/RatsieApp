 CREATE OR REPLACE FUNCTION betIsFinished
( p_betId IN NUMBER )
RETURN INTEGER
AS
  v_finished INTEGER;
BEGIN
  SELECT r.finished 
    INTO v_finished
    FROM Bets b, Races r
    WHERE b.raceId=r.raceId AND b.betId=p_betId;
  
  IF v_finished = 1 THEN
    RETURN 1;
  ELSE
    RETURN 0;
  END IF;
END betIsFinished;