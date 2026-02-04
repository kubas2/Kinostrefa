CREATE TABLE seanse (
  id INT AUTO_INCREMENT PRIMARY KEY,
  idFilmu INT NOT NULL,
  data_start DATETIME NOT NULL,
  sala VARCHAR(50) NOT NULL,
  cena DECIMAL(6,2) NOT NULL,
  FOREIGN KEY (idFilmu) REFERENCES filmy(id) ON DELETE CASCADE
);


CREATE TABLE siedzenia (
  id INT AUTO_INCREMENT PRIMARY KEY,
  rzad INT NOT NULL,
  numer INT NOT NULL,
  UNIQUE (rzad, numer)
);


CREATE TABLE rezerwacje (
  id INT AUTO_INCREMENT PRIMARY KEY,
  idSeansu INT NOT NULL,
  idSiedzenia INT NOT NULL,
  idUser INT NOT NULL,
  status ENUM('zarezerwowana', 'oplacona', 'anulowana') DEFAULT 'zarezerwowana',
  data_rezerwacji DATETIME DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (idSeansu) REFERENCES seanse(id) ON DELETE CASCADE,
  FOREIGN KEY (idSiedzenia) REFERENCES siedzenia(id),
  FOREIGN KEY (idUser) REFERENCES users(id),

  UNIQUE (idSeansu, idSiedzenia)
);



-- TEGO NIE MA BYC W PLIKU 
/*
5️⃣ Pobieranie siatki siedzeń do HTML
Wszystkie miejsca + status:
SELECT 
  si.id,
  si.rzad,
  si.numer,
  r.status
FROM siedzenia si
LEFT JOIN rezerwacje r 
  ON r.idSiedzenia = si.id 
  AND r.idSeansu = :idSeansu;

W HTML:

brak rekordu → 🟢 wolne

zarezerwowana → 🟡 (tymczasowo)

oplacona → 🔴 zajęte

6️⃣ Rezerwacja miejsca (bez race condition 💣)
INSERT INTO rezerwacje (idSeansu, idSiedzenia, idUser)
VALUES (:seans, :siedzenie, :user);

*/