set time_zone =  '+00:00';
select * from status join webcams on webcams.id = status.webcam_id where sunset > now() and http_status = 200 limit 3;
