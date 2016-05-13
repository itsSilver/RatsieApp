CREATE OR REPLACE PROCEDURE update_balance
( p_raceId IN NUMBER ) AS 
v_isPaid NUMBER;
 CURSOR c_bets
      IS
           SELECT *
             FROM Bets
           WHERE p_raceId = raceId;
           
     r_bets   c_bets%ROWTYPE;
     
     
    v_stake NUMBER; 
    suma NUMBER;
    v_userId NUMBER(7);
BEGIN

 OPEN c_bets;

     LOOP
        FETCH c_bets INTO r_bets;
        EXIT WHEN c_bets%NOTFOUND;
          SELECT isPaid, userId into v_isPaid, v_userId
          FROM Tickets
          WHERE ticketId=r_bets.ticketId;
          
           IF ticketIsWon(r_bets.ticketId) = 1 AND v_isPaid = 0 THEN
            
              UPDATE Tickets
              SET isPaid = 1
              WHERE r_bets.ticketId = ticketId;
              
            SELECT stake into v_stake
            FROM Tickets
            WHERE ticketId = r_bets.ticketId;
            suma := v_stake * getTotalOdd(r_bets.ticketId);
          
               
            UPDATE Users
            SET balance = balance + suma
            WHERE userId = v_userId;
            
          END IF;
          
                
      
     END LOOP;

     CLOSE c_bets;
    
  END update_balance;
  /
--Call update_balance(13);