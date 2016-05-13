 CREATE OR REPLACE FUNCTION betIsWon
( p_betId IN NUMBER )
RETURN INTEGER
AS
  v_choice NUMBER;
  v_pos1 NUMBER;
BEGIN
  SELECT b.choice, r.pos1 
    INTO v_choice,v_pos1
    FROM Bets b, Races r
    WHERE b.raceId=r.raceId AND b.betId=p_betId;
  
  IF v_choice = v_pos1 THEN
    RETURN 1;
  END IF;
  RETURN 0;
END betIsWon;
/