CREATE OR REPLACE PROCEDURE addAmount
( p_userId IN NUMBER,
  p_amount IN NUMBER)
AS
BEGIN
  UPDATE Users
  SET balance = balance + p_amount
  WHERE userId = p_userId;
 END addAmount;
 /