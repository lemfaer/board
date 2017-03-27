CREATE TABLE recurrent_appointment (
	id          INTEGER      NOT NULL AUTO_INCREMENT PRIMARY KEY,
	owner_id    INTEGER      NOT NULL,
	mode        VARCHAR(255) NOT NULL,
	time_start  TIME         NOT NULL,
	time_end    TIME         NOT NULL,
	day_start   DATE         NOT NULL,
	day_end     DATE         NOT NULL,
	description TEXT         NOT NULL,
	created     TIMESTAMP    NOT NULL DEFAULT now(),
	updated     TIMESTAMP    NOT NULL DEFAULT now() ON UPDATE now(),
	FOREIGN KEY (owner_id) REFERENCES employee(id)
)

ENGINE = InnoDB;
