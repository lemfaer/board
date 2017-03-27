CREATE TABLE recurrent_except (
	id      INTEGER   NOT NULL AUTO_INCREMENT PRIMARY KEY,
	day     DATE      NOT NULL,
	for_id  INTEGER   NOT NULL,
	created TIMESTAMP NOT NULL DEFAULT now(),
	updated TIMESTAMP NOT NULL DEFAULT now() ON UPDATE now(),
	FOREIGN KEY (for_id) REFERENCES recurrent_appointment(id)
)

ENGINE = InnoDB;
