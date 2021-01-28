DROP TABLE IF EXISTS ranking;
DROP TABLE IF EXISTS matches;
DROP TABLE IF EXISTS teams;

CREATE TABLE teams(
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name VARCHAR(50) NOT NULL
);

CREATE TABLE matches(
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  team0 INTEGER NOT NULL,
  team1 INTEGER NOT NULL,
  score0 INTEGER NOT NULL,
  score1 INTEGER NOT NULL,
  date DATETIME NOT NULL,
  UNIQUE (team0, team1),
  FOREIGN KEY (team0) REFERENCES teams(id),
  FOREIGN KEY (team1) REFERENCES teams(id),
  CONSTRAINT teams_valides CHECK (team0 != team1)
);

CREATE TABLE ranking(
  rank INTEGER NOT NULL,
  team_id INTEGER PRIMARY KEY AUTOINCREMENT,
  match_played_count INTEGER NOT NULL,
  won_match_count INTEGER NOT NULL,
  lost_match_count INTEGER NOT NULL,
  draw_match_count INTEGER NOT NULL,
  goal_for_count INTEGER NOT NULL,
  goal_against_count INTEGER NOT NULL,
  goal_difference INTEGER NOT NULL,
  points INTEGER NOT NULL,
  FOREIGN KEY (team_id) REFERENCES teams(id),
  UNIQUE (rank)
);
