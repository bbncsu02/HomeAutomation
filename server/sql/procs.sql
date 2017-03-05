/* Stored Procedure definitions */
/*
truncate table TemperatureLog;
ALTER TABLE TemperatureLog AUTO_INCREMENT = 1;
*/

drop procedure custom_AddTempLog;
DELIMITER //
CREATE PROCEDURE custom_AddTempLog
(IN in_uuid VARCHAR(30),
 IN in_Temperature DECIMAL(5,2),
 in in_Humidity DECIMAL(5,2))
BEGIN

declare l_id int;

select sensorID into l_id from Sensors where uuid like in_uuid;

if l_id is null then
	/*insert into Sensors(uuid, name, createDate, lastModified) values (in_uuid, in_uuid, now(), now());*/
	insert into Sensors(uuid, name, createDate, lastModified) values (in_uuid, in_uuid, current_timestamp, current_timestamp);
	set l_id = LAST_INSERT_ID();
end if;

insert into TemperatureLog (logDateTime, sensorID, Temperature, Humidity, NaN)
	values(now(), l_id, in_Temperature, in_Humidity, false);

END //
DELIMITER ;




drop procedure custom_GetTempLogColumnHeaders;

DELIMITER //
CREATE PROCEDURE custom_GetTempLogColumnHeaders
()
BEGIN

SELECT 'DateTime' as logDateTime, 
        MAX(CASE WHEN sensorID = 1 THEN name ELSE NULL END) AS s1t, 
        MAX(CASE WHEN sensorID = 1 THEN name ELSE NULL END) AS s1h, 
        MAX(CASE WHEN sensorID = 2 THEN name ELSE NULL END) AS s2t, 
        MAX(CASE WHEN sensorID = 2 THEN name ELSE NULL END) AS s2h
        FROM Sensors GROUP BY logDateTime;

END //
DELIMITER ;





drop procedure custom_GetTempLog;

DELIMITER //
CREATE PROCEDURE custom_GetTempLog
(IN in_begin TIMESTAMP,
 in in_end TIMESTAMP)
BEGIN

DECLARE start_ts TIMESTAMP;
DECLARE end_ts TIMESTAMP;

IF in_end IS NULL THEN
	SET end_ts = CURRENT_TIMESTAMP();
ELSE
	SET end_ts = in_end;
END IF;

IF in_begin IS NULL THEN
	SET start_ts = DATE_SUB(end_ts, INTERVAL 3 DAY);
ELSE
	SET start_ts = in_begin;
END IF;

SELECT 
        myDateTime AS logDateTime, 
        MAX(CASE WHEN sensorID = 1 THEN temperature END) AS temp1, 
        MAX(case when sensorID = 1 then humidity END) as humidity1,
        MAX(case when sensorID = 2 then temperature end) as temp2, 
        MAX(case when sensorID = 2 then humidity end) as humidity2
        FROM (SELECT DATE_SUB(logDateTime, INTERVAL EXTRACT(SECOND FROM logDateTime) SECOND)
           AS myDateTime, sensorID, 
           CASE NaN WHEN 0 THEN temperature ELSE 'NaN' END AS temperature, 
           CASE NaN WHEN 0 THEN humidity ELSE 'NaN' END as humidity 
           from TemperatureLog) as A 
        WHERE myDateTime between start_ts and end_ts
        GROUP BY myDateTime ORDER BY myDateTime;        

END //
DELIMITER ;