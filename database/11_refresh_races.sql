CREATE OR REPLACE PROCEDURE refresh_races AS
  CURSOR c_races
      IS
           SELECT r.raceId, r.rat1Odd, r.rat2Odd, r.rat3Odd, r.rat4Odd, r.rat5Odd, r.rat6Odd
             FROM Races r, DUAL d
           WHERE r.finished = 0 AND r.finishTime < SYSDATE;
           
     r_race   c_races%ROWTYPE;
     clasament pozitionare;
BEGIN
    OPEN c_races;

     LOOP
        FETCH c_races INTO r_race;
        EXIT WHEN c_races%NOTFOUND;
          
          clasament := calc_receResult(r_race.rat1Odd, r_race.rat2Odd, r_race.rat3Odd, r_race.rat4Odd, r_race.rat5Odd, r_race.rat6Odd);
          UPDATE Races
          SET finished = 1,
          pos1=clasament(1),
          pos2=clasament(2),
          pos3=clasament(3),
          pos4=clasament(4),
          pos5=clasament(5),
          pos6=clasament(6)
             WHERE r_race.raceId = raceId;
                  
      update_balance(r_race.raceId);
     END LOOP;

     CLOSE c_races;
  
  
   
END refresh_races;
/
--CALL refresh_races();
/