CREATE TABLE stations (
    id SERIAL PRIMARY KEY,
    shortName VARCHAR(5) NOT NULL,
    longName VARCHAR(255) NOT NULL
);

CREATE TABLE distances (
    fk_parent_stations INTEGER NOT NULL,
    fk_child_stations INTEGER NOT NULL,
    distance FLOAT,

    CONSTRAINT fk_parent FOREIGN KEY (fk_parent_stations)
        REFERENCES stations(id),

    CONSTRAINT fk_child FOREIGN KEY (fk_child_stations)
        REFERENCES stations(id)
);

--INSERT INTO stations (shortName, longName) VALUES ('A', 'Alpha'), ('B', 'Beta');
--INSERT INTO distances VALUES (1, 2, 10.5);